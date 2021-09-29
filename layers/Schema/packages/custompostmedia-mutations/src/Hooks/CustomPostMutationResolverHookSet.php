<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\Hooks;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostMutationResolverHookSet extends AbstractHookSet
{
    protected MediaObjectTypeResolver $mediaTypeResolver;
    protected CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI;

    #[Required]
    public function autowireCustomPostMutationResolverHookSet(
        MediaObjectTypeResolver $mediaTypeResolver,
        CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI,
    ): void {
        $this->mediaTypeResolver = $mediaTypeResolver;
        $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
    }

    protected function init(): void
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
        RelationalTypeResolverInterface $relationalTypeResolver,
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
    public function setOrRemoveFeaturedImage(int | string $customPostID, array $form_data): void
    {
        if (isset($form_data[MutationInputProperties::FEATUREDIMAGE_ID])) {
            if ($featuredImageID = $form_data[MutationInputProperties::FEATUREDIMAGE_ID]) {
                $this->customPostMediaTypeMutationAPI->setFeaturedImage($customPostID, $featuredImageID);
            } else {
                $this->customPostMediaTypeMutationAPI->removeFeaturedImage($customPostID);
            }
        }
    }
}
