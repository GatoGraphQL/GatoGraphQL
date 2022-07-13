<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\FieldQuery\FieldQueryInterpreterInterface as UpstreamFieldQueryInterpreterInterface;

interface FieldQueryInterpreterInterface extends UpstreamFieldQueryInterpreterInterface
{
    public function resolveExpression(
        RelationalTypeResolverInterface $relationalTypeResolver,
        mixed $fieldArgValue,
        ?array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed;
}
