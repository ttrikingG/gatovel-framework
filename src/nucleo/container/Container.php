<?php

namespace nucleo\container;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use Throwable;
use nucleo\exceptions\container\ContainerException;

class Container
{
    private array $bindings = [];

    private array $instances = [];

    private array $resolving = [];

    public function bind(
        string $abstract,
        string|Closure|null $concrete = null
    ): void {
        $this->bindings[$abstract] = [
            'concrete' => $concrete ?? $abstract,
            'singleton' => false,
        ];

        unset($this->instances[$abstract]);
    }

    public function singleton(
        string $abstract,
        string|Closure|null $concrete = null
    ): void {
        $this->bindings[$abstract] = [
            'concrete' => $concrete ?? $abstract,
            'singleton' => true,
        ];

        unset($this->instances[$abstract]);
    }

    public function instance(
        string $abstract,
        object $instance
    ): void {
        $this->ensureCompatible(
            $abstract,
            $instance
        );

        $this->instances[$abstract] = $instance;

        unset($this->bindings[$abstract]);
    }

    public function has(
        string $abstract
    ): bool {
        return isset($this->bindings[$abstract])
            || isset($this->instances[$abstract])
            || class_exists($abstract);
    }

    public function get(
        string $abstract
    ): object {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (in_array($abstract, $this->resolving, true)) {
            $chain = array_merge(
                $this->resolving,
                [$abstract]
            );

            throw new ContainerException(
                'Dependência circular detectada: '
                . implode(' -> ', $chain)
            );
        }

        $this->resolving[] = $abstract;

        try {
            $object = $this->resolve(
                $abstract
            );

            $this->ensureCompatible(
                $abstract,
                $object
            );
        } finally {
            array_pop($this->resolving);
        }

        if (
            isset($this->bindings[$abstract])
            && $this->bindings[$abstract]['singleton'] === true
        ) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    private function resolve(
        string $abstract
    ): object {
        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract]['concrete'];

            if ($concrete instanceof Closure) {
                try {
                    $object = $concrete($this);
                } catch (ContainerException $exception) {
                    throw $exception;
                } catch (Throwable $exception) {
                    throw new ContainerException(
                        "Falha ao resolver o binding {$abstract}.",
                        previous: $exception
                    );
                }

                if (!is_object($object)) {
                    throw new ContainerException(
                        "O binding {$abstract} deve retornar um objeto."
                    );
                }

                return $object;
            }

            return $this->build(
                $concrete
            );
        }

        if (
            interface_exists($abstract)
            || (
                class_exists($abstract)
                && (new ReflectionClass($abstract))->isAbstract()
            )
        ) {
            throw new ContainerException(
                "Nenhum binding registrado para {$abstract}."
            );
        }

        return $this->build(
            $abstract
        );
    }

    private function build(
        string $concrete
    ): object {
        if (!class_exists($concrete)) {
            throw new ContainerException(
                "Classe não encontrada: {$concrete}."
            );
        }

        try {
            $reflection = new ReflectionClass(
                $concrete
            );
        } catch (ReflectionException $exception) {
            throw new ContainerException(
                "Não foi possível refletir a classe {$concrete}.",
                previous: $exception
            );
        }

        if (!$reflection->isInstantiable()) {
            throw new ContainerException(
                "A classe {$concrete} não pode ser instanciada."
            );
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {
            $dependencies[] = $this->resolveParameter(
                $concrete,
                $parameter
            );
        }

        try {
            return $reflection->newInstanceArgs(
                $dependencies
            );
        } catch (ReflectionException $exception) {
            throw new ContainerException(
                "Não foi possível instanciar {$concrete}.",
                previous: $exception
            );
        }
    }

    private function resolveParameter(
        string $concrete,
        ReflectionParameter $parameter
    ): mixed {
        $type = $parameter->getType();

        if (
            $type instanceof ReflectionNamedType
            && !$type->isBuiltin()
        ) {
            return $this->get(
                $type->getName()
            );
        }

        if (
            $type instanceof ReflectionUnionType
            || $type instanceof ReflectionIntersectionType
        ) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            if ($parameter->allowsNull()) {
                return null;
            }

            throw new ContainerException(
                sprintf(
                    'Não foi possível resolver o tipo composto do parâmetro $%s de %s.',
                    $parameter->getName(),
                    $concrete
                )
            );
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->allowsNull()) {
            return null;
        }

        throw new ContainerException(
            sprintf(
                'Não foi possível resolver o parâmetro $%s de %s.',
                $parameter->getName(),
                $concrete
            )
        );
    }

    private function ensureCompatible(
        string $abstract,
        object $object
    ): void {
        if (
            !interface_exists($abstract)
            && !class_exists($abstract)
        ) {
            return;
        }

        if (!$object instanceof $abstract) {
            throw new ContainerException(
                sprintf(
                    'O binding %s resolveu %s, que não é compatível com o contrato solicitado.',
                    $abstract,
                    $object::class
                )
            );
        }
    }
}
