<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\Hooks;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    protected CustomPostTypeAPIInterface $customPostTypeAPI;

    #[Required]
    public function autowireAbstractCustomPostMutationResolverHookSet(
        CustomPostTypeAPIInterface $customPostTypeAPI,
    ): void {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            SchemaDefinitionHelpers::HOOK_UPDATE_SCHEMA_FIELD_ARGS,
            array($this, 'maybeAddSchemaFieldArgs'),
            10,
            4
        );
        $this->hooksAPI->addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
            array($this, 'maybeSetCategories'),
            10,
            2
        );
    }

    public function maybeAddSchemaFieldArgs(
        array $fieldArgs,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        ?ConcreteTypeResolverInterface $entityTypeResolver
    ): array {
        // Only for the specific CPT
        if ($entityTypeResolver === null || get_class($entityTypeResolver) !== get_class($this->getCustomPostTypeResolver())) {
            return $fieldArgs;
        }
        $categoryTypeResolver = $this->getCategoryTypeResolver();
        $fieldArgs[] = [
            SchemaDefinition::ARGNAME_NAME => MutationInputProperties::CATEGORY_IDS,
            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
            SchemaDefinition::ARGNAME_IS_ARRAY => true,
            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                $this->translationAPI->__('The IDs of the categories to set, of type \'%s\'', 'custompost-category-mutations'),
                $categoryTypeResolver->getTypeName()
            )
        ];
        return $fieldArgs;
    }

    abstract protected function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface;
    abstract protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;

    public function maybeSetCategories(int | string $customPostID, array $form_data): void
    {
        // Only for that specific CPT
        if ($this->customPostTypeAPI->getCustomPostType($customPostID) !== $this->getCustomPostType()) {
            return;
        }
        if (!isset($form_data[MutationInputProperties::CATEGORY_IDS])) {
            return;
        }
        $customPostCategoryIDs = $form_data[MutationInputProperties::CATEGORY_IDS];
        $customPostCategoryTypeMutationAPI = $this->getCustomPostCategoryTypeMutationAPI();
        $customPostCategoryTypeMutationAPI->setCategories($customPostID, $customPostCategoryIDs, false);
    }

    abstract protected function getCustomPostType(): string;
    abstract protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface;
}
