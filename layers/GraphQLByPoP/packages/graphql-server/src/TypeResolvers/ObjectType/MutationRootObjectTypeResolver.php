<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\Helpers\TypeResolverHelperInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\MutationRootTypeDataLoader;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use Symfony\Contracts\Service\Attribute\Required;

class MutationRootObjectTypeResolver extends AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;

    private ?TypeResolverHelperInterface $typeResolverHelper = null;
    private ?MutationRootTypeDataLoader $mutationRootTypeDataLoader = null;

    public function setTypeResolverHelper(TypeResolverHelperInterface $typeResolverHelper): void
    {
        $this->typeResolverHelper = $typeResolverHelper;
    }
    protected function getTypeResolverHelper(): TypeResolverHelperInterface
    {
        return $this->typeResolverHelper ??= $this->instanceManager->getInstance(TypeResolverHelperInterface::class);
    }
    public function setMutationRootTypeDataLoader(MutationRootTypeDataLoader $mutationRootTypeDataLoader): void
    {
        $this->mutationRootTypeDataLoader = $mutationRootTypeDataLoader;
    }
    protected function getMutationRootTypeDataLoader(): MutationRootTypeDataLoader
    {
        return $this->mutationRootTypeDataLoader ??= $this->instanceManager->getInstance(MutationRootTypeDataLoader::class);
    }

    //#[Required]
    final public function autowireMutationRootObjectTypeResolver(
        TypeResolverHelperInterface $typeResolverHelper,
        MutationRootTypeDataLoader $mutationRootTypeDataLoader,
    ): void {
        $this->typeResolverHelper = $typeResolverHelper;
        $this->mutationRootTypeDataLoader = $mutationRootTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MutationRoot';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Mutation type, starting from which mutations are executed', 'graphql-server');
    }

    public function getID(object $object): string | int | null
    {
        /** @var MutationRoot */
        $mutationRoot = $object;
        return $mutationRoot->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMutationRootTypeDataLoader();
    }

    public function isFieldNameConditionSatisfiedForSchema(
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        string $fieldName
    ): bool {
        $objectTypeResolverMandatoryFields = $this->getTypeResolverHelper()->getObjectTypeResolverMandatoryFields();
        return
            in_array($fieldName, $objectTypeResolverMandatoryFields)
            || $objectTypeFieldResolver->getFieldMutationResolver($this, $fieldName) !== null;
    }
}
