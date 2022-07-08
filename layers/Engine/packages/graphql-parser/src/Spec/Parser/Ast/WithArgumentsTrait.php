<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Exception\Parser\InvalidDynamicContextException;

trait WithArgumentsTrait
{
    /** @var Argument[] */
    protected array $arguments = [];

    /** @var array<string,Argument> Keep separate to validate that no 2 Arguments have same name */
    protected array $nameArguments = [];

    /** @var array<string,mixed>|null */
    protected ?array $argumentKeyValues = null;


    public function hasArguments(): bool
    {
        return $this->arguments !== [];
    }

    public function hasArgument(string $name): bool
    {
        return array_key_exists($name, $this->nameArguments);
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
        return $this->nameArguments[$name] ?? null;
    }

    /**
     * @throws InvalidDynamicContextException When accessing non-declared Dynamic Variables
     */
    public function getArgumentValue(string $name): mixed
    {
        if ($argument = $this->getArgument($name)) {
            return $argument->getValue();
        }

        return null;
    }

    public function addArgument(Argument $argument): void
    {
        $this->argumentKeyValues = null;
        $this->arguments[] = $argument;
        $this->nameArguments[$argument->getName()] = $argument;
    }

    /**
     * @param Argument[] $arguments
     */
    protected function setArguments(array $arguments): void
    {
        $this->argumentKeyValues = null;
        foreach ($arguments as $argument) {
            $this->arguments[] = $argument;
            $this->nameArguments[$argument->getName()] = $argument;
        };
    }

    /**
     * @return array<string,mixed>
     * @throws InvalidDynamicContextException When accessing non-declared Dynamic Variables
     */
    public function getArgumentKeyValues(): array
    {
        if ($this->argumentKeyValues !== null) {
            return $this->argumentKeyValues;
        }

        $this->argumentKeyValues = [];
        foreach ($this->getArguments() as $argument) {
            $this->argumentKeyValues[$argument->getName()] = $argument->getValue();
        }
        return $this->argumentKeyValues;
    }
}
