<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?MediaObjectTypeResolver $mediaTypeResolver = null;
    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;
    private ?SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver = null;
    private ?RemoveFeaturedImageOnCustomPostMutationResolver $removeFeaturedImageOnCustomPostMutationResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    public function setMediaObjectTypeResolver(MediaObjectTypeResolver $mediaTypeResolver): void
    {
        $this->mediaTypeResolver = $mediaTypeResolver;
    }
    protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        return $this->mediaTypeResolver ??= $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
    }
    public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        return $this->customPostUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }
    public function setSetFeaturedImageOnCustomPostMutationResolver(SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->setFeaturedImageOnCustomPostMutationResolver = $setFeaturedImageOnCustomPostMutationResolver;
    }
    protected function getSetFeaturedImageOnCustomPostMutationResolver(): SetFeaturedImageOnCustomPostMutationResolver
    {
        return $this->setFeaturedImageOnCustomPostMutationResolver ??= $this->instanceManager->getInstance(SetFeaturedImageOnCustomPostMutationResolver::class);
    }
    public function setRemoveFeaturedImageOnCustomPostMutationResolver(RemoveFeaturedImageOnCustomPostMutationResolver $removeFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->removeFeaturedImageOnCustomPostMutationResolver = $removeFeaturedImageOnCustomPostMutationResolver;
    }
    protected function getRemoveFeaturedImageOnCustomPostMutationResolver(): RemoveFeaturedImageOnCustomPostMutationResolver
    {
        return $this->removeFeaturedImageOnCustomPostMutationResolver ??= $this->instanceManager->getInstance(RemoveFeaturedImageOnCustomPostMutationResolver::class);
    }
    public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    //#[Required]
    final public function autowireRootObjectTypeFieldResolver(
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

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'setFeaturedImageOnCustomPost' => $this->translationAPI->__('Set the featured image on a custom post', 'custompostmedia-mutations'),
            'removeFeaturedImageFromCustomPost' => $this->translationAPI->__('Remove the featured image from a custom post', 'custompostmedia-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'setFeaturedImageOnCustomPost' => [
                MutationInputProperties::CUSTOMPOST_ID => $this->getIdScalarTypeResolver(),
                MutationInputProperties::MEDIA_ITEM_ID => $this->getIdScalarTypeResolver(),
            ],
            'removeFeaturedImageFromCustomPost' => [
                MutationInputProperties::CUSTOMPOST_ID => $this->getIdScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['setFeaturedImageOnCustomPost' => MutationInputProperties::CUSTOMPOST_ID],
            ['removeFeaturedImageFromCustomPost' => MutationInputProperties::CUSTOMPOST_ID]
                => $this->translationAPI->__('The ID of the custom post', 'custompostmedia-mutations'),
            ['setFeaturedImageOnCustomPost' => MutationInputProperties::MEDIA_ITEM_ID]
                => sprintf(
                    $this->translationAPI->__('The ID of the featured image, of type \'%s\'', 'custompostmedia-mutations'),
                    $this->getMediaTypeResolver()->getTypeName()
                ),
            default
                => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['setFeaturedImageOnCustomPost' => MutationInputProperties::CUSTOMPOST_ID],
            ['removeFeaturedImageFromCustomPost' => MutationInputProperties::CUSTOMPOST_ID],
            ['setFeaturedImageOnCustomPost' => MutationInputProperties::MEDIA_ITEM_ID]
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'setFeaturedImageOnCustomPost' => $this->getSetFeaturedImageOnCustomPostMutationResolver(),
            'removeFeaturedImageFromCustomPost' => $this->getRemoveFeaturedImageOnCustomPostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'setFeaturedImageOnCustomPost',
            'removeFeaturedImageFromCustomPost'
                => $this->getCustomPostUnionTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
