<?php

namespace nucleo\http;

use RuntimeException;

class UploadedFile
{
    public function __construct(
        private readonly string $originalName,
        private readonly string $temporaryPath,
        private readonly int $error,
        private readonly int $size
    ) {
    }

    public function originalName(): string
    {
        return $this->originalName;
    }

    public function temporaryPath(): string
    {
        return $this->temporaryPath;
    }

    /**
     * Returns the extension declared in the original filename.
     *
     * This value is user-controlled metadata and must not be used
     * to determine the real content type of the uploaded file.
     */
    public function extension(): string
    {
        return strtolower(
            pathinfo(
                $this->originalFilename(),
                PATHINFO_EXTENSION
            )
        );
    }

    /**
     * Detects the MIME type from the actual temporary file content.
     */
    public function mimeType(): ?string
    {
        if (!$this->hasTemporaryFile()) {
            return null;
        }

        $finfo = finfo_open(
            FILEINFO_MIME_TYPE
        );

        if ($finfo === false) {
            return null;
        }

        try {
            $mimeType = finfo_file(
                $finfo,
                $this->temporaryPath
            );
        } finally {
            finfo_close(
                $finfo
            );
        }

        if (
            !is_string($mimeType)
            || $mimeType === ''
        ) {
            return null;
        }

        return $mimeType;
    }

    /**
     * Returns the size reported by PHP in $_FILES.
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * Returns the size observed directly from the temporary file.
     */
    public function actualSize(): ?int
    {
        if (!$this->hasTemporaryFile()) {
            return null;
        }

        $size = filesize(
            $this->temporaryPath
        );

        return is_int($size)
            ? $size
            : null;
    }

    public function error(): int
    {
        return $this->error;
    }

    public function errorMessage(): string
    {
        return match ($this->error) {
            UPLOAD_ERR_OK =>
                'O upload foi concluído com sucesso.',

            UPLOAD_ERR_INI_SIZE =>
                'O arquivo excede o limite configurado pelo servidor.',

            UPLOAD_ERR_FORM_SIZE =>
                'O arquivo excede o limite definido pelo formulário.',

            UPLOAD_ERR_PARTIAL =>
                'O arquivo foi enviado apenas parcialmente.',

            UPLOAD_ERR_NO_FILE =>
                'Nenhum arquivo foi enviado.',

            UPLOAD_ERR_NO_TMP_DIR =>
                'O diretório temporário de uploads não está disponível.',

            UPLOAD_ERR_CANT_WRITE =>
                'O servidor não conseguiu gravar o arquivo temporário.',

            UPLOAD_ERR_EXTENSION =>
                'Uma extensão do PHP interrompeu o upload.',

            default =>
                'O upload falhou com um erro desconhecido.',
        };
    }

    public function isValid(): bool
    {
        return $this->error === UPLOAD_ERR_OK
            && $this->size >= 0
            && $this->temporaryPath !== ''
            && is_file(
                $this->temporaryPath
            )
            && is_uploaded_file(
                $this->temporaryPath
            );
    }

    /**
     * Generates a cryptographically random server-side filename.
     *
     * The original extension is preserved only as metadata for
     * convenience. Applications must validate the real MIME type
     * before deciding whether an uploaded file is acceptable.
     */
    public function generateFilename(): string
    {
        $filename = bin2hex(
            random_bytes(16)
        );

        $extension = $this->extension();

        if ($extension === '') {
            return $filename;
        }

        if (!$this->isSafeExtension($extension)) {
            return $filename;
        }

        return $filename
            . '.'
            . $extension;
    }

    public function move(
        string $directory,
        ?string $filename = null
    ): string {
        if (!$this->isValid()) {
            throw new RuntimeException(
                'O arquivo enviado não é um upload válido: '
                . $this->errorMessage()
            );
        }

        $directory = $this->prepareDirectory(
            $directory
        );

        $filename = $filename === null
            ? $this->generateFilename()
            : $this->validateDestinationFilename(
                $filename
            );

        $destination = $directory
            . DIRECTORY_SEPARATOR
            . $filename;

        if (
            file_exists($destination)
            || is_link($destination)
        ) {
            throw new RuntimeException(
                'Já existe um arquivo com esse nome no destino.'
            );
        }

        if (
            !move_uploaded_file(
                $this->temporaryPath,
                $destination
            )
        ) {
            throw new RuntimeException(
                'Não foi possível mover o arquivo enviado.'
            );
        }

        return $destination;
    }

    private function hasTemporaryFile(): bool
    {
        return $this->temporaryPath !== ''
            && is_file(
                $this->temporaryPath
            );
    }

    private function originalFilename(): string
    {
        $filename = str_replace(
            '\\',
            '/',
            $this->originalName
        );

        return basename(
            $filename
        );
    }

    private function prepareDirectory(
        string $directory
    ): string {
        if (
            $directory === ''
            || str_contains(
                $directory,
                "\0"
            )
        ) {
            throw new RuntimeException(
                'O diretório de destino do upload é inválido.'
            );
        }

        $directory = rtrim(
            $directory,
            '/\\'
        );

        if ($directory === '') {
            throw new RuntimeException(
                'O diretório de destino do upload é inválido.'
            );
        }

        if (
            !is_dir($directory)
            && !mkdir(
                $directory,
                0775,
                true
            )
            && !is_dir($directory)
        ) {
            throw new RuntimeException(
                'Não foi possível criar o diretório de destino.'
            );
        }

        $realDirectory = realpath(
            $directory
        );

        if (
            $realDirectory === false
            || !is_dir($realDirectory)
        ) {
            throw new RuntimeException(
                'O diretório de destino do upload é inválido.'
            );
        }

        if (!is_writable($realDirectory)) {
            throw new RuntimeException(
                'O diretório de destino não possui permissão de escrita.'
            );
        }

        return $realDirectory;
    }

    private function validateDestinationFilename(
        string $filename
    ): string {
        if (
            $filename === ''
            || $filename === '.'
            || $filename === '..'
            || str_contains(
                $filename,
                "\0"
            )
            || str_contains(
                $filename,
                '/'
            )
            || str_contains(
                $filename,
                '\\'
            )
            || $this->containsControlCharacter(
                $filename
            )
        ) {
            throw new RuntimeException(
                'O nome do arquivo de destino é inválido.'
            );
        }

        if (
            basename($filename)
            !== $filename
        ) {
            throw new RuntimeException(
                'O nome do arquivo de destino é inválido.'
            );
        }

        return $filename;
    }

    private function containsControlCharacter(
        string $value
    ): bool {
        return preg_match(
            '/[\x00-\x1F\x7F]/',
            $value
        ) === 1;
    }

    private function isSafeExtension(
        string $extension
    ): bool {
        return preg_match(
            '/^[a-z0-9]{1,20}$/',
            $extension
        ) === 1;
    }
}
