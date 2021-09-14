<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\MutationRootTypeDataLoader;

class MutationRootObjectTypeResolver extends AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'MutationRoot';
    }

    public function getSchemaTypeDescription(): ?string
    {
        /**
         * Not needed anymore since duplicating Root entries into QueryRoot and MutationRoot
         * when injecting them via "addEntriesForFields"
         * @see ...
         */
        // return $this->translationAPI->__('Mutation type, starting from which mutations are executed. Available when \'nested mutations\' is disabled', 'graphql-server');
        return $this->translationAPI->__('Mutation type, starting from which mutations are executed', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var MutationRoot */
        $mutationRoot = $object;
        return $mutationRoot->getID();
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return MutationRootTypeDataLoader::class;
    }

    public function isFieldNameConditionSatisfiedForSchema(
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        string $fieldName
    ): bool {
        return
            // Fields "id", "self" and "__typename" are mandatory, so they must both
            // also be allowed for the MutationRoot, even if they are not mutations!
            in_array($fieldName, ['id', 'self', '__typename'])
            || $objectTypeFieldResolver->resolveFieldMutationResolverClass($this, $fieldName) !== null;
    }
}
