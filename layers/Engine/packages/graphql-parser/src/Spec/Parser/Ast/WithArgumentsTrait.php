<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\ExtendedSpec\Execution\ValueResolutionPromiseInterface;
use stdClass;

trait WithArgumentsTrait
{
    /** @var Argument[] */
    protected array $arguments = [];

    /** @var array<string,Argument> Keep separate to validate that no 2 Arguments have same name */
    protected array $nameArguments = [];

    /** @var array<string,mixed>|null */
    protected ?array $argumentKeyValues = null;
    protected ?bool $hasArgumentReferencingPromise = null;
    protected ?bool $hasArgumentReferencingResolvedOnEngineIterationPromise = null;
    protected ?bool $hasArgumentReferencingResolvedOnObjectPromise = null;

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

    public function getArgumentValue(string $name): mixed
    {
        if ($argument = $this->getArgument($name)) {
            return $argument->getValue();
        }

        return null;
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

    public function hasArgumentReferencingPromise(): bool
    {
        if ($this->hasArgumentReferencingPromise === null) {
            $this->hasArgumentReferencingPromise = $this->hasArgumentReferencingResolvedOnEngineIterationPromise()
                || $this->hasArgumentReferencingResolvedOnObjectPromise();
        }
        return $this->hasArgumentReferencingPromise;
    }

    public function hasArgumentReferencingResolvedOnEngineIterationPromise(): bool
    {
        if ($this->hasArgumentReferencingResolvedOnEngineIterationPromise === null) {
            $this->hasArgumentReferencingResolvedOnEngineIterationPromise = $this->doHasArgumentReferencingResolvedOnEngineIterationPromise($this->getArgumentKeyValues());
        }
        return $this->hasArgumentReferencingResolvedOnEngineIterationPromise;
    }

    protected function doHasArgumentReferencingResolvedOnEngineIterationPromise(array $values): mixed
    {
        foreach ($values as $value) {
            if ($value instanceof ValueResolutionPromiseInterface) {
                return true;
            }
            if (is_array($value) && $this->doHasArgumentReferencingResolvedOnEngineIterationPromise($value)) {
                return true;
            }
            if ($value instanceof stdClass && $this->doHasArgumentReferencingResolvedOnEngineIterationPromise((array)$value)) {
                return true;
            }
        }
        return false;
    }

    public function hasArgumentReferencingResolvedOnObjectPromise(): bool
    {
        if ($this->hasArgumentReferencingResolvedOnObjectPromise === null) {
            $this->hasArgumentReferencingResolvedOnObjectPromise = $this->doHasArgumentReferencingResolvedOnObjectPromise($this->getArgumentKeyValues());
        }
        return $this->hasArgumentReferencingResolvedOnObjectPromise;
    }

    protected function doHasArgumentReferencingResolvedOnObjectPromise(array $values): mixed
    {
        foreach ($values as $value) {
            if ($value instanceof ValueResolutionPromiseInterface) {
                return true;
            }
            if (is_array($value) && $this->doHasArgumentReferencingResolvedOnObjectPromise($value)) {
                return true;
            }
            if ($value instanceof stdClass && $this->doHasArgumentReferencingResolvedOnObjectPromise((array)$value)) {
                return true;
            }
        }
        return false;
    }
}
