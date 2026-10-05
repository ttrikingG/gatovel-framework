<?php

namespace nucleo\loadSupport;

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

    public function extension(): string
    {
        return strtolower(
            pathinfo(
                $this->safeFilename(
                    $this->originalName
                ),
                PATHINFO_EXTENSION
            )
        );
    }

    public function mimeType(): ?string
    {
        if (
            $this->temporaryPath === ''
            || !is_file($this->temporaryPath)
        ) {
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

        return is_string($mimeType)
            ? $mimeType
            : null;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function error(): int
    {
        return $this->error;
    }

    public function isValid(): bool
    {
        return $this->error === UPLOAD_ERR_OK
            && $this->temporaryPath !== ''
            && is_uploaded_file(
                $this->temporaryPath
            );
    }

    public function move(
        string $directory,
        ?string $filename = null
    ): string {
        if (!$this->isValid()) {
            throw new RuntimeException(
                'O arquivo enviado não é um upload válido.'
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

        if (!is_writable($directory)) {
            throw new RuntimeException(
                'O diretório de destino não possui permissão de escrita.'
            );
        }

        $filename = $this->safeFilename(
            $filename ?? $this->originalName
        );

        if (
            $filename === ''
            || $filename === '.'
            || $filename === '..'
        ) {
            throw new RuntimeException(
                'O nome do arquivo de destino é inválido.'
            );
        }

        $destination = $directory
            . DIRECTORY_SEPARATOR
            . $filename;

        if (file_exists($destination)) {
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

    private function safeFilename(
        string $filename
    ): string {
        $filename = str_replace(
            '\\',
            '/',
            $filename
        );

        return basename(
            $filename
        );
    }
}
