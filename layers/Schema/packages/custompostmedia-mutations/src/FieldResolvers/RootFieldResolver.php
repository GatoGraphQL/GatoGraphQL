<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\Object\RootTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPosts\TypeResolvers\Object\CustomPostUnionTypeResolver;
use PoPSchema\Media\TypeResolvers\Object\MediaTypeResolver;

class RootFieldResolver extends AbstractQueryableFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected MediaTypeResolver $mediaTypeResolver
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
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

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'setFeaturedImageOnCustomPost' => $this->translationAPI->__('Set the featured image on a custom post', 'custompostmedia-mutations'),
            'removeFeaturedImageFromCustomPost' => $this->translationAPI->__('Remove the featured image from a custom post', 'custompostmedia-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'setFeaturedImageOnCustomPost' => SchemaDefinition::TYPE_ID,
            'removeFeaturedImageFromCustomPost' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
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
        return parent::getSchemaFieldArgs($relationalTypeResolver, $fieldName);
    }

    public function resolveFieldMutationResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
                return SetFeaturedImageOnCustomPostMutationResolver::class;
            case 'removeFeaturedImageFromCustomPost':
                return RemoveFeaturedImageOnCustomPostMutationResolver::class;
        }

        return parent::resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
            case 'removeFeaturedImageFromCustomPost':
                return CustomPostUnionTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}
