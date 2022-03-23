<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

trait WithArgumentsTrait
{
    /** @var Argument[] */
    protected array $arguments;

    /** @var array<string,mixed>|null */
    protected ?array $keyValueArguments = null;


    public function hasArguments(): bool
    {
        return count($this->arguments) > 0;
    }

    public function hasArgument(string $name): bool
    {
        return array_key_exists($name, $this->arguments);
    }

    /**
     * @return Argument[]
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
        $this->keyValueArguments = null;
        $this->arguments = $arguments;
    }

    public function addArgument(Argument $argument): void
    {
        $this->arguments[] = $argument;
    }

    /**
     * @return array<string,mixed>
     */
    public function getKeyValueArguments(): array
    {
        if ($this->keyValueArguments !== null) {
            return $this->keyValueArguments;
        }

        $this->keyValueArguments = [];
        foreach ($this->getArguments() as $argument) {
            $this->keyValueArguments[$argument->getName()] = $argument->getValue()->getValue();
        }
        return $this->keyValueArguments;
    }
}
