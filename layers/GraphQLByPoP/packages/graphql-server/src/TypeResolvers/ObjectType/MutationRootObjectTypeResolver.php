<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\Helpers\TypeResolverHelperInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\MutationRootObjectTypeDataLoader;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;

class MutationRootObjectTypeResolver extends AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;
    use RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;

    private ?TypeResolverHelperInterface $typeResolverHelper = null;
    private ?MutationRootObjectTypeDataLoader $mutationRootObjectTypeDataLoader = null;

    final protected function getTypeResolverHelper(): TypeResolverHelperInterface
    {
        if ($this->typeResolverHelper === null) {
            /** @var TypeResolverHelperInterface */
            $typeResolverHelper = $this->instanceManager->getInstance(TypeResolverHelperInterface::class);
            $this->typeResolverHelper = $typeResolverHelper;
        }
        return $this->typeResolverHelper;
    }
    final protected function getMutationRootObjectTypeDataLoader(): MutationRootObjectTypeDataLoader
    {
        if ($this->mutationRootObjectTypeDataLoader === null) {
            /** @var MutationRootObjectTypeDataLoader */
            $mutationRootObjectTypeDataLoader = $this->instanceManager->getInstance(MutationRootObjectTypeDataLoader::class);
            $this->mutationRootObjectTypeDataLoader = $mutationRootObjectTypeDataLoader;
        }
        return $this->mutationRootObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MutationRoot';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Mutation type, starting from which mutations are executed', 'graphql-server');
    }

    public function getID(object $object): string|int|null
    {
        /** @var MutationRoot */
        $mutationRoot = $object;
        return $mutationRoot->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMutationRootObjectTypeDataLoader();
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

    /**
     * Remove the IdentifiableObject interface
     *
     * @param InterfaceTypeFieldResolverInterface[] $interfaceTypeFieldResolvers
     * @return InterfaceTypeFieldResolverInterface[]
     */
    final protected function consolidateAllImplementedInterfaceTypeFieldResolvers(
        array $interfaceTypeFieldResolvers,
    ): array {
        return $this->removeIdentifiableObjectInterfaceTypeFieldResolver(
            parent::consolidateAllImplementedInterfaceTypeFieldResolvers($interfaceTypeFieldResolvers),
        );
    }
}
