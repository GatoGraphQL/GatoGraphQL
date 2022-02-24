<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class ObjectTrace extends AbstractTrace implements ObjectTraceInterface
{
    public function __construct(
        string|int $id,
        /** @var array<string,mixed> */
        array $data = [],
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        protected string $field,
        protected string|int $objectID,
        protected ?string $directive = null,
    ) {
        parent::__construct(
            $id,
            $data,
        );
    }

    public function getRelationalTypeResolver(): RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getObjectID(): string|int
    {
        return $this->objectID;
    }

    public function getDirective(): ?string
    {
        return $this->directive;
    }
}
