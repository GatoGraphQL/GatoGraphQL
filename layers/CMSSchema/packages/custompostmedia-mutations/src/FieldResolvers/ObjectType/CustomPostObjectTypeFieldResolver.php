<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\CustomPostSetFeaturedImageFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;

class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?MediaObjectTypeResolver $mediaObjectTypeResolver = null;
    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;
    private ?SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver = null;
    private ?RemoveFeaturedImageOnCustomPostMutationResolver $removeFeaturedImageOnCustomPostMutationResolver = null;
    private ?CustomPostSetFeaturedImageFilterInputObjectTypeResolver $customPostSetFeaturedImageFilterInputObjectTypeResolver = null;

    final public function setMediaObjectTypeResolver(MediaObjectTypeResolver $mediaObjectTypeResolver): void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        return $this->mediaObjectTypeResolver ??= $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
    }
    final public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        return $this->customPostUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }
    final public function setSetFeaturedImageOnCustomPostMutationResolver(SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->setFeaturedImageOnCustomPostMutationResolver = $setFeaturedImageOnCustomPostMutationResolver;
    }
    final protected function getSetFeaturedImageOnCustomPostMutationResolver(): SetFeaturedImageOnCustomPostMutationResolver
    {
        return $this->setFeaturedImageOnCustomPostMutationResolver ??= $this->instanceManager->getInstance(SetFeaturedImageOnCustomPostMutationResolver::class);
    }
    final public function setRemoveFeaturedImageOnCustomPostMutationResolver(RemoveFeaturedImageOnCustomPostMutationResolver $removeFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->removeFeaturedImageOnCustomPostMutationResolver = $removeFeaturedImageOnCustomPostMutationResolver;
    }
    final protected function getRemoveFeaturedImageOnCustomPostMutationResolver(): RemoveFeaturedImageOnCustomPostMutationResolver
    {
        return $this->removeFeaturedImageOnCustomPostMutationResolver ??= $this->instanceManager->getInstance(RemoveFeaturedImageOnCustomPostMutationResolver::class);
    }
    final public function setCustomPostSetFeaturedImageFilterInputObjectTypeResolver(CustomPostSetFeaturedImageFilterInputObjectTypeResolver $customPostSetFeaturedImageFilterInputObjectTypeResolver): void
    {
        $this->customPostSetFeaturedImageFilterInputObjectTypeResolver = $customPostSetFeaturedImageFilterInputObjectTypeResolver;
    }
    final protected function getCustomPostSetFeaturedImageFilterInputObjectTypeResolver(): CustomPostSetFeaturedImageFilterInputObjectTypeResolver
    {
        return $this->customPostSetFeaturedImageFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostSetFeaturedImageFilterInputObjectTypeResolver::class);
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
            'setFeaturedImage' => $this->__('Set the featured image on the custom post', 'custompostmedia-mutations'),
            'removeFeaturedImage' => $this->__('Remove the featured image on the custom post', 'custompostmedia-mutations'),
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

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'setFeaturedImage' => [
                'input' => $this->getCustomPostSetFeaturedImageFilterInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['setFeaturedImage' => 'input'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in the field argument in the query
     */
    public function validateMutationOnObject(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return match ($fieldName) {
            'setFeaturedImage',
            'removeFeaturedImage'
                => true,
            default
                => parent::validateMutationOnObject($objectTypeResolver, $fieldName),
        };
    }

    // @todo Remove this function
    protected function prepareFieldDataAccessorForObject(
        FieldDataAccessorInterface $fieldDataAccessorForObject,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        object $object,
    ): void {
        parent::prepareFieldDataAccessorForObject(
            $fieldDataAccessorForObject,
            $objectTypeResolver,
            $field,
            $object,
        );
        $customPost = $object;
        switch ($field->getName()) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                $fieldDataAccessorForObject->addValue(MutationInputProperties::CUSTOMPOST_ID, $objectTypeResolver->getID($customPost));
                break;
        }
    }

    /**
     * @param array<string,mixed> $fieldDataForObject
     */
    public function prepareFieldDataForObject(
        array &$fieldDataForObject,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        object $object,
    ): void {
        parent::prepareFieldDataForObject(
            $fieldDataForObject,
            $objectTypeResolver,
            $field,
            $object,
        );
        $customPost = $object;
        switch ($field->getName()) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                $fieldDataForObject[MutationInputProperties::CUSTOMPOST_ID] = $objectTypeResolver->getID($customPost);
                break;
        }
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'setFeaturedImage' => $this->getSetFeaturedImageOnCustomPostMutationResolver(),
            'removeFeaturedImage' => $this->getRemoveFeaturedImageOnCustomPostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'setFeaturedImage',
            'removeFeaturedImage'
                => $this->getCustomPostUnionTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
