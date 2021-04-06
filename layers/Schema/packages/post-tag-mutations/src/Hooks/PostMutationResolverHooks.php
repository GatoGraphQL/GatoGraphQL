<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\Hooks;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\PostTagMutations\Facades\PostTagTypeAPIFacade;
use PoPSchema\PostTagMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;

class PostMutationResolverHooks extends AbstractHookSet
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        protected PostTagTypeResolver $postTagTypeResolver
    ) {
        parent::__construct($hooksAPI, $translationAPI);
    }

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            SchemaDefinitionHelpers::HOOK_UPDATE_SCHEMA_FIELD_ARGS,
            array($this, 'maybeAddSchemaFieldArgs'),
            10,
            3
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
        string $fieldName
    ): array {
        // Only for Posts, not for other CPTs
        if ($this->postTagTypeResolver !== $typeResolver) {
            return $fieldArgs;
        }
        $fieldArgs[] = [
            SchemaDefinition::ARGNAME_NAME => MutationInputProperties::TAG_IDS,
            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                $this->translationAPI->__('The IDs of the tags (of type %s)', 'custompost-mutations'),
                $this->postTagTypeResolver->getTypeName()
            ),
        ];
        return $fieldArgs;
    }

    public function maybeSetTags(int | string $customPostID, array $form_data): void
    {
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        if (isset($form_data[MutationInputProperties::TAG_IDS])) {
            $postTagIDs = $form_data[MutationInputProperties::TAG_IDS];
            $append = $form_data[MutationInputProperties::APPEND] ?? false;
            $postTagTypeAPI->setTags($customPostID, $postTagIDs, $append);
        }
    }
}
