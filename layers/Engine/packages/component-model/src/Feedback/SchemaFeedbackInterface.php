<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface SchemaFeedbackInterface extends QueryFeedbackInterface
{
    public function getRelationalTypeResolver(): RelationalTypeResolverInterface;
    public function getField(): FieldInterface;
    public function getDirective(): ?string;
    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getNested(): array;
}
