<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\Helpers\TypeResolverHelperInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\MutationRootTypeDataLoader;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

class MutationRootObjectTypeResolver extends AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;

    /**
     * List of fieldNames that are mandatory to all ObjectTypeResolvers
     *
     * @var string[]
     */
    protected array $objectTypeResolverMandatoryFields;

    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        SchemaNamespacingServiceInterface $schemaNamespacingService,
        SchemaDefinitionServiceInterface $schemaDefinitionService,
        FeedbackMessageStoreInterface $feedbackMessageStore,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        ErrorProviderInterface $errorProvider,
        TypeResolverHelperInterface $typeResolverHelper,
        RootObjectTypeResolver $rootObjectTypeResolver,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $schemaNamespacingService,
            $schemaDefinitionService,
            $feedbackMessageStore,
            $fieldQueryInterpreter,
            $errorProvider,
            $rootObjectTypeResolver,
        );
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

    public function getRelationalTypeDataLoaderClass(): string
    {
        return MutationRootTypeDataLoader::class;
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
