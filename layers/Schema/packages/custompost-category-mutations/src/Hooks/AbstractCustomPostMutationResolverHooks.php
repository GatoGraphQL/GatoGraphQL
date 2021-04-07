<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\Hooks;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;

abstract class AbstractCustomPostMutationResolverHooks extends AbstractHookSet
{
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
        TypeResolverInterface $typeResolver,
        string $fieldName,
        ?string $entityTypeResolverClass
    ): array {
        // Only for the specific CPT
        if ($entityTypeResolverClass !== $this->getTypeResolverClass()) {
            return $fieldArgs;
        }
        $instanceManager = InstanceManagerFacade::getInstance();
        $categoryTypeResolverClass = $this->getCategoryTypeResolverClass();
        /** @var TypeResolverInterface */
        $categoryTypeResolver = $instanceManager->getInstance($categoryTypeResolverClass);
        $fieldArgs[] = [
            SchemaDefinition::ARGNAME_NAME => MutationInputProperties::CATEGORY_IDS,
            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                $this->translationAPI->__('The IDs of the categories to set, of type \'%s\'', 'custompost-category-mutations'),
                $categoryTypeResolver->getTypeName()
            )
        ];
        return $fieldArgs;
    }

    abstract protected function getTypeResolverClass(): string;
    abstract protected function getCategoryTypeResolverClass(): string;

    public function maybeSetCategories(int | string $customPostID, array $form_data): void
    {
        // Only for that specific CPT
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($customPostID) !== $this->getCustomPostType()) {
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
