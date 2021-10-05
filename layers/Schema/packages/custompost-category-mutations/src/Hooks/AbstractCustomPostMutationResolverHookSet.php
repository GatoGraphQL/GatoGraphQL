<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\Hooks;

use PoP\ComponentModel\FieldResolvers\ObjectType\HookNames;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    protected CustomPostTypeAPIInterface $customPostTypeAPI;
    protected IDScalarTypeResolver $idScalarTypeResolver;

    #[Required]
    final public function autowireAbstractCustomPostMutationResolverHookSet(
        CustomPostTypeAPIInterface $customPostTypeAPI,
        IDScalarTypeResolver $idScalarTypeResolver,
    ): void {
        $this->customPostTypeAPI = $customPostTypeAPI;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            HookNames::FIELD_ARG_NAME_RESOLVERS,
            array($this, 'maybeAddSchemaFieldArgNameResolvers'),
            10,
            4
        );
        $this->hooksAPI->addFilter(
            HookNames::FIELD_ARG_DESCRIPTION,
            array($this, 'maybeAddSchemaFieldArgDescription'),
            10,
            5
        );
        $this->hooksAPI->addFilter(
            HookNames::FIELD_ARG_TYPE_MODIFIERS,
            array($this, 'maybeAddSchemaFieldArgTypeModifiers'),
            10,
            5
        );
        $this->hooksAPI->addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
            array($this, 'maybeSetCategories'),
            10,
            2
        );
    }

    public function maybeAddSchemaFieldArgNameResolvers(
        array $schemaFieldArgNameResolvers,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): array {
        // Only for the specific combinations of Type and fieldName
        if (!$this->mustAddSchemaFieldArgs($objectTypeResolver, $fieldName)) {
            return $schemaFieldArgNameResolvers;
        }
        $schemaFieldArgNameResolvers[MutationInputProperties::CATEGORY_IDS] = $this->idScalarTypeResolver;
        return $schemaFieldArgNameResolvers;
    }

    public function maybeAddSchemaFieldArgDescription(
        ?string $schemaFieldArgDescription,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
    ): ?string {
        // Only for the newly added fieldArgName
        if ($fieldArgName !== MutationInputProperties::CATEGORY_IDS || !$this->mustAddSchemaFieldArgs($objectTypeResolver, $fieldName)) {
            return $schemaFieldArgDescription;
        }
        return sprintf(
            $this->translationAPI->__('The IDs of the categories to set, of type \'%s\'', 'custompost-category-mutations'),
            $this->getCategoryTypeResolver()->getMaybeNamespacedTypeName()
        );
    }

    public function maybeAddSchemaFieldArgTypeModifiers(
        int $schemaFieldArgTypeModifiers,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
    ): int {
        // Only for the newly added fieldArgName
        if ($fieldArgName !== MutationInputProperties::CATEGORY_IDS || !$this->mustAddSchemaFieldArgs($objectTypeResolver, $fieldName)) {
            return $schemaFieldArgTypeModifiers;
        }
        return SchemaTypeModifiers::IS_ARRAY;
    }

    abstract protected function mustAddSchemaFieldArgs(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): bool;

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
