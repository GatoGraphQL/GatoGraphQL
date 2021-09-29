<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\Hooks;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
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
            array($this, 'maybeSetTags'),
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
        $fieldArgs[] = [
            SchemaDefinition::ARGNAME_NAME => MutationInputProperties::TAGS,
            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
            SchemaDefinition::ARGNAME_IS_ARRAY => true,
            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The tags to set', 'custompost-tag-mutations'),
        ];
        return $fieldArgs;
    }

    abstract protected function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface;

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
