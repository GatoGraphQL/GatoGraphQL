<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoPSchema\PostCategories\RelationalTypeDataLoaders\ObjectType\PostCategoryTypeDataLoader;
use PoPSchema\Categories\TypeResolvers\ObjectType\AbstractCategoryObjectTypeResolver;

class PostCategoryObjectTypeResolver extends AbstractCategoryObjectTypeResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function __construct(
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface $schemaNamespacingService,
        \PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface $schemaDefinitionService,
        \PoP\ComponentModel\Schema\FeedbackMessageStoreInterface $feedbackMessageStore,
        \PoP\ComponentModel\Schema\FieldQueryInterpreterInterface $fieldQueryInterpreter,
        \PoP\ComponentModel\ErrorHandling\ErrorProviderInterface $errorProvider,
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
        );
    }
    
    public function getTypeName(): string
    {
        return 'PostCategory';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a category, added to a post', 'post-categories');
    }

    public function getRelationalTypeDataLoaderClass(): RelationalTypeDataLoaderInterface
    {
        return PostCategoryTypeDataLoader::class;
    }
}
