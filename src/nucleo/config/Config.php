<?php

namespace nucleo\config;

use nucleo\exceptions\configuration\ConfigurationException;

class Config
{
    private static array $items = [];

    private static ?string $configPath = null;

    /**
     * Define o diretório onde estão os arquivos
     * de configuração da aplicação.
     */
    public static function setPath(string $path): void
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        if (!is_dir($path)) {
            throw new ConfigurationException(
                "Diretório de configuração não encontrado: {$path}"
            );
        }

        self::$configPath = $path;
    }

    /**
     * Carrega todos os arquivos PHP existentes
     * no diretório de configuração.
     */
    public static function load(): void
    {
        if (self::$configPath === null) {
            throw new ConfigurationException(
                'O diretório de configuração não foi definido.'
            );
        }

        $files = glob(
            self::$configPath . DIRECTORY_SEPARATOR . '*.php'
        );

        if ($files === false) {
            throw new ConfigurationException(
                'Não foi possível localizar os arquivos de configuração.'
            );
        }

        foreach ($files as $file) {
            $name = pathinfo(
                $file,
                PATHINFO_FILENAME
            );

            $config = require $file;

            if (!is_array($config)) {
                throw new ConfigurationException(
                    "O arquivo de configuração {$name}.php deve retornar um array."
                );
            }

            self::$items[$name] = $config;
        }
    }

    /**
     * Retorna uma configuração utilizando
     * notação por ponto.
     *
     * Exemplo:
     *
     * Config::get('database.host');
     */
    public static function get(
        string $key,
        mixed $default = null
    ): mixed {
        $segments = explode('.', $key);

        $value = self::$items;

        foreach ($segments as $segment) {
            if (
                !is_array($value) ||
                !array_key_exists($segment, $value)
            ) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Verifica se determinada configuração existe.
     */
    public static function has(string $key): bool
    {
        $segments = explode('.', $key);

        $value = self::$items;

        foreach ($segments as $segment) {
            if (
                !is_array($value) ||
                !array_key_exists($segment, $value)
            ) {
                return false;
            }

            $value = $value[$segment];
        }

        return true;
    }

    /**
     * Define uma configuração em tempo de execução.
     */
    public static function set(
        string $key,
        mixed $value
    ): void {
        $segments = explode('.', $key);

        $config = &self::$items;

        foreach ($segments as $segment) {
            if (
                !isset($config[$segment]) ||
                !is_array($config[$segment])
            ) {
                $config[$segment] = [];
            }

            $config = &$config[$segment];
        }

        $config = $value;
    }

    /**
     * Retorna todas as configurações carregadas.
     */
    public static function all(): array
    {
        return self::$items;
    }
}
