<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\Hooks;

use PoP\ComponentModel\FieldResolvers\ObjectType\HookNames;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    protected CustomPostTypeAPIInterface $customPostTypeAPI;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    final public function autowireAbstractCustomPostMutationResolverHookSet(
        CustomPostTypeAPIInterface $customPostTypeAPI,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->customPostTypeAPI = $customPostTypeAPI;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
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
            array($this, 'maybeSetTags'),
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
        $schemaFieldArgNameResolvers[MutationInputProperties::TAGS] = $this->stringScalarTypeResolver;
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
        if ($fieldArgName !== MutationInputProperties::TAGS || !$this->mustAddSchemaFieldArgs($objectTypeResolver, $fieldName)) {
            return $schemaFieldArgDescription;
        }
        return $this->translationAPI->__('The tags to set', 'custompost-tag-mutations');
    }

    public function maybeAddSchemaFieldArgTypeModifiers(
        int $schemaFieldArgTypeModifiers,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
    ): int {
        // Only for the newly added fieldArgName
        if ($fieldArgName !== MutationInputProperties::TAGS || !$this->mustAddSchemaFieldArgs($objectTypeResolver, $fieldName)) {
            return $schemaFieldArgTypeModifiers;
        }
        return SchemaTypeModifiers::IS_ARRAY;
    }

    abstract protected function mustAddSchemaFieldArgs(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): bool;

    public function maybeSetTags(int | string $customPostID, array $form_data): void
    {
        // Only for that specific CPT
        if ($this->customPostTypeAPI->getCustomPostType($customPostID) !== $this->getCustomPostType()) {
            return;
        }
        if (!isset($form_data[MutationInputProperties::TAGS])) {
            return;
        }
        $customPostTags = $form_data[MutationInputProperties::TAGS];
        $customPostTagTypeMutationAPI = $this->getCustomPostTagTypeMutationAPI();
        $customPostTagTypeMutationAPI->setTags($customPostID, $customPostTags, false);
    }

    abstract protected function getCustomPostType(): string;
    abstract protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface;
}
