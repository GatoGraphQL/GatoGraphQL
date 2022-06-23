<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Tracing;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class Trace implements TraceInterface
{
    public function __construct(
        protected string|int $id,
        /** @var array<string,mixed> */
        protected array $data = [],
        protected ?Directive $directive = null,
        /** @var array<string|int,FieldInterface[]>|null */
        protected ?array $idFields = null,
        protected ?RelationalTypeResolverInterface $relationalTypeResolver = null,
        protected ?FieldInterface $field = null,
        protected string|int|null $objectID = null,
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

    public function getDirective(): ?Directive
    {
        return $this->directive;
    }

    /**
     * @return array<string|int,FieldInterface[]>|null
     */
    public function getIDFields(): ?array
    {
        return $this->idFields;
    }

    public function getRelationalTypeResolver(): ?RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }

    public function getField(): ?FieldInterface
    {
        return $this->field;
    }

    public function getObjectID(): string|int|null
    {
        return $this->objectID;
    }
}
