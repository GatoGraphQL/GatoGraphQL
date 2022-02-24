<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Tracing;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class Trace implements TraceInterface
{
    public function __construct(
        protected string|int $id,
        /** @var array<string,mixed> */
        protected array $data = [],
        protected ?RelationalTypeResolverInterface $relationalTypeResolver = null,
        protected ?string $field = null,
        protected string|int|null $objectID = null,
        protected ?string $directive = null,
    ) {
    }

    public function getID(): string|int
    {
        return $this->id;
    }

    /**
     * @return array<string,mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getRelationalTypeResolver(): ?RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function getObjectID(): string|int|null
    {
        return $this->objectID;
    }

    public function getDirective(): ?string
    {
        return $this->directive;
    }
}
