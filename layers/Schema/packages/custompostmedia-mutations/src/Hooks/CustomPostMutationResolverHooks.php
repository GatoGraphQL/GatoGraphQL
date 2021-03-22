<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\Hooks;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPostMediaMutations\Facades\CustomPostMediaTypeAPIFacade;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;

class CustomPostMutationResolverHooks extends AbstractHookSet
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        protected MediaTypeResolver $mediaTypeResolver
    ) {
        parent::__construct($hooksAPI, $translationAPI);
    }

    protected function init()
    {
        $this->hooksAPI->addFilter(
            SchemaDefinitionHelpers::HOOK_UPDATE_SCHEMA_FIELD_ARGS,
            array($this, 'getSchemaFieldArgs'),
            10,
            3
        );
        $this->hooksAPI->addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
            array($this, 'setOrRemoveFeaturedImage'),
            10,
            2
        );
    }

    public function getSchemaFieldArgs(
        array $fieldArgs,
        TypeResolverInterface $typeResolver,
        string $fieldName
    ): array {
        $fieldArgs[] = [
            SchemaDefinition::ARGNAME_NAME => MutationInputProperties::FEATUREDIMAGE_ID,
            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                $this->translationAPI->__('The ID of the featured image (of type %s)', 'custompost-mutations'),
                $this->mediaTypeResolver->getTypeName()
            ),
        ];
        return $fieldArgs;
    }

    /**
     * If entry "featuredImageID" has an ID, set it. If it is null, remove it
     */
    public function setOrRemoveFeaturedImage(mixed $customPostID, array $form_data): void
    {
        $customPostMediaTypeAPI = CustomPostMediaTypeAPIFacade::getInstance();
        if (isset($form_data[MutationInputProperties::FEATUREDIMAGE_ID])) {
            if ($featuredImageID = $form_data[MutationInputProperties::FEATUREDIMAGE_ID]) {
                $customPostMediaTypeAPI->setFeaturedImage($customPostID, $featuredImageID);
            } else {
                $customPostMediaTypeAPI->removeFeaturedImage($customPostID);
            }
        }
    }
}
