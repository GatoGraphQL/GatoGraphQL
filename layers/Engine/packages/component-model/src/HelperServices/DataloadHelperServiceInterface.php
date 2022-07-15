<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\Feedback\SchemaFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface DataloadHelperServiceInterface
{
    /**
     * Accept RelationalTypeResolverInterface as param, instead of the more natural
     * ObjectTypeResolverInterface, to make it easy within the application to check
     * for this result without checking in advance what's the typeResolver.
     *
     * If the FeedbackStore is provided, report errors in the GraphQL query,
     * such as nested fields requested on leaf fields:
     *
     *   `{ id { id } }`
     *
     * This is optional as this method is called in multiple places,
     * but the error needs to be added only once.
     */
    public function getTypeResolverFromSubcomponentField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
        ?SchemaFeedbackStore $schemaFeedbackStore,
    ): ?RelationalTypeResolverInterface;
}
