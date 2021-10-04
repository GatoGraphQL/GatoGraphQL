<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
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
    protected IDScalarTypeResolver $idScalarTypeResolver;

    #[Required]
    final public function autowireCustomPostObjectTypeFieldResolver(
        MediaObjectTypeResolver $mediaTypeResolver,
        CustomPostUnionTypeResolver $customPostUnionTypeResolver,
        SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver,
        RemoveFeaturedImageOnCustomPostMutationResolver $removeFeaturedImageOnCustomPostMutationResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
    ): void {
        $this->mediaTypeResolver = $mediaTypeResolver;
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
        $this->setFeaturedImageOnCustomPostMutationResolver = $setFeaturedImageOnCustomPostMutationResolver;
        $this->removeFeaturedImageOnCustomPostMutationResolver = $removeFeaturedImageOnCustomPostMutationResolver;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
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

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'setFeaturedImage' => $this->translationAPI->__('Set the featured image on the custom post', 'custompostmedia-mutations'),
            'removeFeaturedImage' => $this->translationAPI->__('Remove the featured image on the custom post', 'custompostmedia-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'setFeaturedImage',
            'removeFeaturedImage'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'setFeaturedImage' => [
                MutationInputProperties::MEDIA_ITEM_ID => $this->idScalarTypeResolver,
            ],
            default => parent::getFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['setFeaturedImage' => MutationInputProperties::MEDIA_ITEM_ID] => sprintf(
                $this->translationAPI->__('The ID of the featured image, of type \'%s\'', 'custompostmedia-mutations'),
                $this->mediaTypeResolver->getTypeName()
            ),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['setFeaturedImage' => MutationInputProperties::MEDIA_ITEM_ID] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
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
