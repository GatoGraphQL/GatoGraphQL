<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected MediaObjectTypeResolver $mediaTypeResolver;
    protected CustomPostUnionTypeResolver $customPostUnionTypeResolver;
    protected SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver;
    protected RemoveFeaturedImageOnCustomPostMutationResolver $removeFeaturedImageOnCustomPostMutationResolver;

    #[Required]
    public function autowireCustomPostObjectTypeFieldResolver(
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
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'setFeaturedImage',
            'removeFeaturedImage',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'setFeaturedImage' => $this->translationAPI->__('Set the featured image on the custom post', 'custompostmedia-mutations'),
            'removeFeaturedImage' => $this->translationAPI->__('Remove the featured image on the custom post', 'custompostmedia-mutations'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'setFeaturedImage',
            'removeFeaturedImage',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
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
        return parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
    }

    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                return true;
        }
        return parent::validateMutationOnObject($objectTypeResolver, $fieldName);
    }

    protected function getFieldArgsToExecuteMutation(
        array $fieldArgs,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName
    ): array {
        $fieldArgs = parent::getFieldArgsToExecuteMutation(
            $fieldArgs,
            $objectTypeResolver,
            $object,
            $fieldName
        );
        $customPost = $object;
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                $fieldArgs[MutationInputProperties::CUSTOMPOST_ID] = $objectTypeResolver->getID($customPost);
                break;
        }

        return $fieldArgs;
    }

    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface {
        switch ($fieldName) {
            case 'setFeaturedImage':
                return $this->setFeaturedImageOnCustomPostMutationResolver;
            case 'removeFeaturedImage':
                return $this->removeFeaturedImageOnCustomPostMutationResolver;
        }

        return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                return $this->customPostUnionTypeResolver;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
