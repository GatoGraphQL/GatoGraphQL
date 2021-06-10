<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\FieldResolvers;

use PoP\Hooks\HooksAPIInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver;

class CustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        protected MediaTypeResolver $mediaTypeResolver
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
        );
    }

    public function getClassesToAttachTo(): array
    {
        return array(IsCustomPostFieldInterfaceResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'setFeaturedImage',
            'removeFeaturedImage',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'setFeaturedImage' => $this->translationAPI->__('Set the featured image on the custom post', 'custompostmedia-mutations'),
            'removeFeaturedImage' => $this->translationAPI->__('Remove the featured image on the custom post', 'custompostmedia-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'setFeaturedImage' => SchemaDefinition::TYPE_ID,
            'removeFeaturedImage' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'setFeaturedImage',
            'removeFeaturedImage',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'setFeaturedImage':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::MEDIA_ITEM_ID,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $this->translationAPI->__('The ID of the featured image, of type \'%s\'', 'custompostmedia-mutations'),
                            $this->mediaTypeResolver->getTypeName()
                        ),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ];
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    /**
     * Validated the mutation on the resultItem because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnResultItem(
        TypeResolverInterface $typeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                return true;
        }
        return parent::validateMutationOnResultItem($typeResolver, $fieldName);
    }

    protected function getFieldArgsToExecuteMutation(
        array $fieldArgs,
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName
    ): array {
        $fieldArgs = parent::getFieldArgsToExecuteMutation(
            $fieldArgs,
            $typeResolver,
            $resultItem,
            $fieldName
        );
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                $fieldArgs[MutationInputProperties::CUSTOMPOST_ID] = $typeResolver->getID($customPost);
                break;
        }

        return $fieldArgs;
    }

    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImage':
                return SetFeaturedImageOnCustomPostMutationResolver::class;
            case 'removeFeaturedImage':
                return RemoveFeaturedImageOnCustomPostMutationResolver::class;
        }

        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                return CustomPostUnionTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
