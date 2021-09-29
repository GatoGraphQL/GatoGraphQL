<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    protected MediaObjectTypeResolver $mediaTypeResolver;
    protected CustomPostUnionTypeResolver $customPostUnionTypeResolver;
    protected SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver;
    protected RemoveFeaturedImageOnCustomPostMutationResolver $removeFeaturedImageOnCustomPostMutationResolver;

    #[Required]
    public function autowireRootObjectTypeFieldResolver(
        MediaObjectTypeResolver $mediaTypeResolver,
        CustomPostUnionTypeResolver $customPostUnionTypeResolver,
        SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver,
        RemoveFeaturedImageOnCustomPostMutationResolver $removeFeaturedImageOnCustomPostMutationResolver,
    ): void {
        $this->mediaTypeResolver = $mediaTypeResolver;
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
        $this->setFeaturedImageOnCustomPostMutationResolver = $setFeaturedImageOnCustomPostMutationResolver;
        $this->removeFeaturedImageOnCustomPostMutationResolver = $removeFeaturedImageOnCustomPostMutationResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
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

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'setFeaturedImageOnCustomPost' => $this->translationAPI->__('Set the featured image on a custom post', 'custompostmedia-mutations'),
            'removeFeaturedImageFromCustomPost' => $this->translationAPI->__('Remove the featured image from a custom post', 'custompostmedia-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
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
        return parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
    }

    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface {
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
                return $this->setFeaturedImageOnCustomPostMutationResolver;
            case 'removeFeaturedImageFromCustomPost':
                return $this->removeFeaturedImageOnCustomPostMutationResolver;
        }

        return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
            case 'removeFeaturedImageFromCustomPost':
                return $this->customPostUnionTypeResolver;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
