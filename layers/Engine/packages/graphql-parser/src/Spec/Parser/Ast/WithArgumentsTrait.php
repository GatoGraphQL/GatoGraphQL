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
        $this->arguments[] = $argument;
        $this->nameArguments[$argument->getName()] = $argument;
    }

    /**
     * @param Argument[] $arguments
     */
    protected function setArguments(array $arguments): void
    {
        foreach ($arguments as $argument) {
            $this->arguments[] = $argument;
            $this->nameArguments[$argument->getName()] = $argument;
        };
    }
}
