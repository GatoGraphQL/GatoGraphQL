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
            array($this, 'maybeAddFieldArgNameResolvers'),
            10,
            4
        );
        $this->hooksAPI->addFilter(
            HookNames::FIELD_ARG_DESCRIPTION,
            array($this, 'maybeAddFieldArgDescription'),
            10,
            5
        );
        $this->hooksAPI->addFilter(
            HookNames::FIELD_ARG_TYPE_MODIFIERS,
            array($this, 'maybeAddFieldArgTypeModifiers'),
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

    public function maybeAddFieldArgNameResolvers(
        array $fieldArgNameResolvers,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): array {
        // Only for the specific combinations of Type and fieldName
        if (!$this->mustAddFieldArgs($objectTypeResolver, $fieldName)) {
            return $fieldArgNameResolvers;
        }
        $fieldArgNameResolvers[MutationInputProperties::CATEGORY_IDS] = $this->idScalarTypeResolver;
        return $fieldArgNameResolvers;
    }

    public function maybeAddFieldArgDescription(
        ?string $fieldArgDescription,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
    ): ?string {
        // Only for the newly added fieldArgName
        if ($fieldArgName !== MutationInputProperties::CATEGORY_IDS || !$this->mustAddFieldArgs($objectTypeResolver, $fieldName)) {
            return $fieldArgDescription;
        }
        return sprintf(
            $this->translationAPI->__('The IDs of the categories to set, of type \'%s\'', 'custompost-category-mutations'),
            $this->getCategoryTypeResolver()->getMaybeNamespacedTypeName()
        );
    }

    public function maybeAddFieldArgTypeModifiers(
        int $fieldArgTypeModifiers,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
    ): int {
        // Only for the newly added fieldArgName
        if ($fieldArgName !== MutationInputProperties::CATEGORY_IDS || !$this->mustAddFieldArgs($objectTypeResolver, $fieldName)) {
            return $fieldArgTypeModifiers;
        }
        return SchemaTypeModifiers::IS_ARRAY;
    }

    abstract protected function mustAddFieldArgs(
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
