<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\FieldResolvers;

use PoP\Hooks\HooksAPIInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver;

class RootFieldResolver extends AbstractQueryableFieldResolver
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
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        if (EngineComponentConfiguration::disableRedundantRootTypeMutationFields()) {
            return [];
        }
        return [
            'setFeaturedImageOnCustomPost',
            'removeFeaturedImageFromCustomPost',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'setFeaturedImageOnCustomPost' => $this->translationAPI->__('Set the featured image on a custom post', 'custompostmedia-mutations'),
            'removeFeaturedImageFromCustomPost' => $this->translationAPI->__('Remove the featured image from a custom post', 'custompostmedia-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'setFeaturedImageOnCustomPost' => SchemaDefinition::TYPE_ID,
            'removeFeaturedImageFromCustomPost' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $setRemoveFeaturedImageSchemaFieldArgs = [
            [
                SchemaDefinition::ARGNAME_NAME => MutationInputProperties::CUSTOMPOST_ID,
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The ID of the custom post', 'custompostmedia-mutations'),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
        ];
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
                return array_merge(
                    $setRemoveFeaturedImageSchemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => MutationInputProperties::MEDIA_ITEM_ID,
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                                $this->translationAPI->__('The ID of the featured image, of type \'%s\'', 'custompostmedia-mutations'),
                                $this->mediaTypeResolver->getTypeName()
                            ),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'removeFeaturedImageFromCustomPost':
                return $setRemoveFeaturedImageSchemaFieldArgs;
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
                return SetFeaturedImageOnCustomPostMutationResolver::class;
            case 'removeFeaturedImageFromCustomPost':
                return RemoveFeaturedImageOnCustomPostMutationResolver::class;
        }

        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
            case 'removeFeaturedImageFromCustomPost':
                return CustomPostUnionTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
