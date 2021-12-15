<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

trait AstArgumentsTrait
{
    /** @var array<string,Argument> */
    protected array $arguments;

    /** @var array<string,mixed>|null */
    private ?array $argumentsCache = null;


    public function hasArguments(): bool
    {
        return count($this->arguments) > 0;
    }

    public function hasArgument(string $name): bool
    {
        return array_key_exists($name, $this->arguments);
    }

    /**
     * @return array<string,Argument>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getArgument(string $name): ?Argument
    {
        return $this->arguments[$name] ?? null;
    }

    public function getArgumentValue(string $name): mixed
    {
        if ($argument = $this->getArgument($name)) {
            return $argument->getValue()->getValue();
        }

        return null;
    }

    /**
     * @param Argument[] $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = [];
        $this->argumentsCache = null;

        foreach ($arguments as $argument) {
            $this->addArgument($argument);
        }
    }

    public function addArgument(Argument $argument): void
    {
        $this->arguments[$argument->getName()] = $argument;
    }

    /**
     * @return array<string,mixed>
     */
    public function getKeyValueArguments(): array
    {
        if ($this->argumentsCache !== null) {
            return $this->argumentsCache;
        }

        $this->argumentsCache = [];

        foreach ($this->getArguments() as $argument) {
            $this->argumentsCache[$argument->getName()] = $argument->getValue()->getValue();
        }

        return $this->argumentsCache;
    }
}
