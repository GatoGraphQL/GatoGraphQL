<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use GraphQLByPoP\GraphQLServer\Helpers\TypeResolverHelperInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\MutationRootTypeDataLoader;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;

class MutationRootObjectTypeResolver extends AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;

    /**
     * List of fieldNames that are mandatory to all ObjectTypeResolvers
     *
     * @var string[]
     */
    protected array $objectTypeResolverMandatoryFields;
    protected MutationRootTypeDataLoader $mutationRootTypeDataLoader;

    #[Required]
    public function autowireMutationRootObjectTypeResolver(
        TypeResolverHelperInterface $typeResolverHelper,
        MutationRootTypeDataLoader $mutationRootTypeDataLoader,
    ): void {
        $this->mutationRootTypeDataLoader = $mutationRootTypeDataLoader;
        $this->objectTypeResolverMandatoryFields = $typeResolverHelper->getObjectTypeResolverMandatoryFields();
    }

    public function getTypeName(): string
    {
        return 'MutationRoot';
    }

    public function getSchemaTypeDescription(): ?string
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
        return $this->mutationRootTypeDataLoader;
    }

    public function isFieldNameConditionSatisfiedForSchema(
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        string $fieldName
    ): bool {
        return
            in_array($fieldName, $this->objectTypeResolverMandatoryFields)
            || $objectTypeFieldResolver->getFieldMutationResolver($this, $fieldName) !== null;
    }
}
