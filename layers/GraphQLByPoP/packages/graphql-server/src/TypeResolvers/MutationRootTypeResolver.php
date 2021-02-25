<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use GraphQLByPoP\GraphQLServer\TypeDataLoaders\MutationRootTypeDataLoader;

class MutationRootTypeResolver extends AbstractUseRootAsSourceForSchemaTypeResolver
{
    use ReservedNameTypeResolverTrait;

    public function getTypeName(): string
    {
        return 'MutationRoot';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Mutation type, starting from which mutations are executed', 'graphql-server');
    }

    public function getID(object $resultItem)
    {
        /** @var MutationRoot */
        $mutationRoot = $resultItem;
        return $mutationRoot->getID();
    }

    public function getTypeDataLoaderClass(): string
    {
        return MutationRootTypeDataLoader::class;
    }

    protected function isFieldNameConditionSatisfiedForSchema(FieldResolverInterface $fieldResolver, string $fieldName): bool
    {
        return
            // Fields "id", "self" and "__typename" are mandatory, so they must both
            // also be allowed for the MutationRoot, even if they are not mutations!
            in_array($fieldName, ['id', 'self', '__typename'])
            || $fieldResolver->resolveFieldMutationResolverClass($this, $fieldName) !== null;
    }
}
