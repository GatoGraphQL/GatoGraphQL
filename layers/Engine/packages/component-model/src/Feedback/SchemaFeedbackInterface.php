<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface SchemaFeedbackInterface extends QueryFeedbackInterface
{
    public function getRelationalTypeResolver(): RelationalTypeResolverInterface;
    /**
     * @return string[]
     */
    public function getFields(): array;
}
