<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue;

use PoPBackbone\GraphQLParser\Parser\Ast\AbstractAst;
use PoPBackbone\GraphQLParser\Parser\Location;

class Variable extends AbstractAst
{
    private bool $hasDefaultValue = false;

    private mixed $defaultValue = null;

    public function __construct(
        private string $name,
        private string $type,
        private bool $nullable,
        private bool $isArray,
        private bool $arrayElementNullable,
        Location $location,
    ) {
        parent::__construct($location);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTypeName(): string
    {
        return $this->type;
    }

    public function setTypeName(string $type): void
    {
        $this->type = $type;
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function setIsArray(bool $isArray): void
    {
        $this->isArray = $isArray;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function setNullable(bool $nullable): void
    {
        $this->nullable = $nullable;
    }

    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(mixed $defaultValue): void
    {
        $this->hasDefaultValue = true;
        $this->defaultValue = $defaultValue;
    }

    public function isArrayElementNullable(): bool
    {
        return $this->arrayElementNullable;
    }

    public function setArrayElementNullable(bool $arrayElementNullable): void
    {
        $this->arrayElementNullable = $arrayElementNullable;
    }
}
