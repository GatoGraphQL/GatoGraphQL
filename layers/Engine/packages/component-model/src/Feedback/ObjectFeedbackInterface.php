<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface ObjectFeedbackInterface extends QueryFeedbackInterface
{
    public function getRelationalTypeResolver(): RelationalTypeResolverInterface;
    public function getField(): FieldInterface;
    public function getObjectID(): string | int;
    public function getDirective(): ?string;
    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getNested(): array;
}
