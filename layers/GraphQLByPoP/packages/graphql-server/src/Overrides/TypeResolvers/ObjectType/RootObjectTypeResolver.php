<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Overrides\TypeResolvers\ObjectType;

use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver as UpstreamRootObjectTypeResolver;

/**
 * Optimization: Only calculate fields "queryRoot" and "mutationRoot"
 * when using Query/MutationRoot instead of Root
 */
class RootObjectTypeResolver extends UpstreamRootObjectTypeResolver
{
    // @todo Review! It was commented because global fields should be also found (eg: "objectAddEntry")
    // protected function calculateObjectTypeFieldResolversForField(string $field): array
    // {
    //     $enableNestedMutations = App::getState('nested-mutations-enabled');
    //     /**
    //      * Watch out: The field is not provided fieldArgs,
    //      * that's why there's no need to parse $field to get the fieldName!
    //      */
    //     if (
    //         !$enableNestedMutations && !in_array($field, [
    //         'queryRoot',
    //         'mutationRoot',
    //         'id',
    //         ])
    //     ) {
    //         return [];
    //     }
    //     return parent::calculateObjectTypeFieldResolversForField($field);
    // }
}
