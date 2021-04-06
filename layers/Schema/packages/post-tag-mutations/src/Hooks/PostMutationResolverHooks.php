<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\Hooks;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\PostTagMutations\Facades\PostTagTypeMutationAPIFacade;
use PoPSchema\PostTagMutations\MutationResolvers\MutationInputProperties;

class PostMutationResolverHooks extends AbstractHookSet
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
            array($this, 'maybeSetTags'),
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
        // Only for Posts, not for other CPTs
        if ($entityTypeResolverClass !== PostTypeResolver::class) {
            return $fieldArgs;
        }
        $fieldArgs[] = [
            SchemaDefinition::ARGNAME_NAME => MutationInputProperties::TAGS,
            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The tags to set', 'custompost-mutations'),
        ];
        return $fieldArgs;
    }

    public function maybeSetTags(int | string $customPostID, array $form_data): void
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($customPostID) !== $postTypeAPI->getPostCustomPostType()) {
            return;
        }
        if (!isset($form_data[MutationInputProperties::TAGS])) {
            return;
        }
        $postTags = $form_data[MutationInputProperties::TAGS];
        $postTagTypeMutationAPI = PostTagTypeMutationAPIFacade::getInstance();
        $postTagTypeMutationAPI->setTags($customPostID, $postTags, false);
    }
}
