<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class SchemaTrace extends AbstractTrace implements SchemaTraceInterface
{
    public function __construct(
        string|int $id,
        /** @var array<string,mixed> */
        array $data = [],
        protected RelationalTypeResolverInterface $relationalTypeResolver,
        protected string $field,
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

    public function getDirective(): ?string
    {
        return $this->directive;
    }
}
