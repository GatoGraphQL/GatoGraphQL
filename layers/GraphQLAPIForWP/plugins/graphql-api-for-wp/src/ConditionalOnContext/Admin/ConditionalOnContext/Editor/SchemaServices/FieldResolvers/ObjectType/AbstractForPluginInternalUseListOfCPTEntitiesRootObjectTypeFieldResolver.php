<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

/**
 * These fields must be accessed by the plugin only,
 * they are unavailable otherwise (even to the admin
 * user in the wp-admin GraphiQL client).
 */
abstract class AbstractForPluginInternalUseListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractListOfCPTEntitiesRootObjectTypeFieldResolver
{
    public function resolveCanProcess(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        if (!parent::resolveCanProcess(
            $objectTypeResolver,
            $field,
        )) {
            return false;
        }
        return false;
    }
}
