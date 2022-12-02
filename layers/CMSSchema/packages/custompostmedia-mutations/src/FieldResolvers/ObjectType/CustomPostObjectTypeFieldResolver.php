<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\Module;
use PoPCMSSchema\CustomPostMediaMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableRemoveFeaturedImageFromCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableSetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageFromCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\CustomPostSetFeaturedImageFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use stdClass;

class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?MediaObjectTypeResolver $mediaObjectTypeResolver = null;
    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;
    private ?SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver = null;
    private ?RemoveFeaturedImageFromCustomPostMutationResolver $removeFeaturedImageFromCustomPostMutationResolver = null;
    private ?CustomPostSetFeaturedImageFilterInputObjectTypeResolver $customPostSetFeaturedImageFilterInputObjectTypeResolver = null;
    private ?PayloadableSetFeaturedImageOnCustomPostMutationResolver $payloadableSetFeaturedImageOnCustomPostMutationResolver = null;
    private ?PayloadableRemoveFeaturedImageFromCustomPostMutationResolver $payloadableRemoveFeaturedImageFromCustomPostMutationResolver = null;
    private ?CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver $customPostSetFeaturedImageMutationPayloadObjectTypeResolver = null;
    private ?CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver $customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver = null;

    final public function setMediaObjectTypeResolver(MediaObjectTypeResolver $mediaObjectTypeResolver): void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        /** @var MediaObjectTypeResolver */
        return $this->mediaObjectTypeResolver ??= $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
    }
    final public function setCustomPostUnionTypeResolver(CustomPostUnionTypeResolver $customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        /** @var CustomPostUnionTypeResolver */
        return $this->customPostUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }
    final public function setSetFeaturedImageOnCustomPostMutationResolver(SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->setFeaturedImageOnCustomPostMutationResolver = $setFeaturedImageOnCustomPostMutationResolver;
    }
    final protected function getSetFeaturedImageOnCustomPostMutationResolver(): SetFeaturedImageOnCustomPostMutationResolver
    {
        /** @var SetFeaturedImageOnCustomPostMutationResolver */
        return $this->setFeaturedImageOnCustomPostMutationResolver ??= $this->instanceManager->getInstance(SetFeaturedImageOnCustomPostMutationResolver::class);
    }
    final public function setRemoveFeaturedImageFromCustomPostMutationResolver(RemoveFeaturedImageFromCustomPostMutationResolver $removeFeaturedImageFromCustomPostMutationResolver): void
    {
        $this->removeFeaturedImageFromCustomPostMutationResolver = $removeFeaturedImageFromCustomPostMutationResolver;
    }
    final protected function getRemoveFeaturedImageFromCustomPostMutationResolver(): RemoveFeaturedImageFromCustomPostMutationResolver
    {
        /** @var RemoveFeaturedImageFromCustomPostMutationResolver */
        return $this->removeFeaturedImageFromCustomPostMutationResolver ??= $this->instanceManager->getInstance(RemoveFeaturedImageFromCustomPostMutationResolver::class);
    }
    final public function setCustomPostSetFeaturedImageFilterInputObjectTypeResolver(CustomPostSetFeaturedImageFilterInputObjectTypeResolver $customPostSetFeaturedImageFilterInputObjectTypeResolver): void
    {
        $this->customPostSetFeaturedImageFilterInputObjectTypeResolver = $customPostSetFeaturedImageFilterInputObjectTypeResolver;
    }
    final protected function getCustomPostSetFeaturedImageFilterInputObjectTypeResolver(): CustomPostSetFeaturedImageFilterInputObjectTypeResolver
    {
        /** @var CustomPostSetFeaturedImageFilterInputObjectTypeResolver */
        return $this->customPostSetFeaturedImageFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostSetFeaturedImageFilterInputObjectTypeResolver::class);
    }
    final public function setPayloadableSetFeaturedImageOnCustomPostMutationResolver(PayloadableSetFeaturedImageOnCustomPostMutationResolver $payloadableSetFeaturedImageOnCustomPostMutationResolver): void
    {
        $this->payloadableSetFeaturedImageOnCustomPostMutationResolver = $payloadableSetFeaturedImageOnCustomPostMutationResolver;
    }
    final protected function getPayloadableSetFeaturedImageOnCustomPostMutationResolver(): PayloadableSetFeaturedImageOnCustomPostMutationResolver
    {
        /** @var PayloadableSetFeaturedImageOnCustomPostMutationResolver */
        return $this->payloadableSetFeaturedImageOnCustomPostMutationResolver ??= $this->instanceManager->getInstance(PayloadableSetFeaturedImageOnCustomPostMutationResolver::class);
    }
    final public function setPayloadableRemoveFeaturedImageFromCustomPostMutationResolver(PayloadableRemoveFeaturedImageFromCustomPostMutationResolver $payloadableRemoveFeaturedImageFromCustomPostMutationResolver): void
    {
        $this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver = $payloadableRemoveFeaturedImageFromCustomPostMutationResolver;
    }
    final protected function getPayloadableRemoveFeaturedImageFromCustomPostMutationResolver(): PayloadableRemoveFeaturedImageFromCustomPostMutationResolver
    {
        /** @var PayloadableRemoveFeaturedImageFromCustomPostMutationResolver */
        return $this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver ??= $this->instanceManager->getInstance(PayloadableRemoveFeaturedImageFromCustomPostMutationResolver::class);
    }
    final public function setCustomPostSetFeaturedImageMutationPayloadObjectTypeResolver(CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver $customPostSetFeaturedImageMutationPayloadObjectTypeResolver): void
    {
        $this->customPostSetFeaturedImageMutationPayloadObjectTypeResolver = $customPostSetFeaturedImageMutationPayloadObjectTypeResolver;
    }
    final protected function getCustomPostSetFeaturedImageMutationPayloadObjectTypeResolver(): CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver
    {
        /** @var CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver */
        return $this->customPostSetFeaturedImageMutationPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver::class);
    }
    final public function setCustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver(CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver $customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver): void
    {
        $this->customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver = $customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver;
    }
    final protected function getCustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver(): CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver
    {
        /** @var CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver */
        return $this->customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'setFeaturedImage' => [
                MutationInputProperties::INPUT => $this->getCustomPostSetFeaturedImageFilterInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['setFeaturedImage' => MutationInputProperties::INPUT] => SchemaTypeModifiers::MANDATORY,
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

    /**
     * @param array<string,mixed> $fieldArgsForMutationForObject
     * @return array<string,mixed>
     */
    public function prepareFieldArgsForMutationForObject(
        array $fieldArgsForMutationForObject,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        object $object,
    ): array {
        $fieldArgsForMutationForObject = parent::prepareFieldArgsForMutationForObject(
            $fieldArgsForMutationForObject,
            $objectTypeResolver,
            $field,
            $object,
        );
        $customPost = $object;
        switch ($field->getName()) {
            case 'removeFeaturedImage':
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT] ??= new stdClass();
                break;
        }
        switch ($field->getName()) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::CUSTOMPOST_ID} = $objectTypeResolver->getID($customPost);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    /**
     * Because "removeFeaturedImage" receives no arguments, it doesn't
     * know it needs to pass the "input" entry to the MutationResolver,
     * so explicitly set it up then.
     */
    public function getFieldArgsInputObjectSubpropertyName(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): ?string {
        return match ($field->getName()) {
            'removeFeaturedImage' => MutationInputProperties::INPUT,
            default => parent::getFieldArgsInputObjectSubpropertyName(
                $objectTypeResolver,
                $field,
            ),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMediaMutations = $moduleConfiguration->usePayloadableCustomPostMediaMutations();
        return match ($fieldName) {
            'setFeaturedImage' => $usePayloadableCustomPostMediaMutations
                ? $this->getPayloadableSetFeaturedImageOnCustomPostMutationResolver()
                : $this->getSetFeaturedImageOnCustomPostMutationResolver(),
            'removeFeaturedImage' => $usePayloadableCustomPostMediaMutations
                ? $this->getPayloadableRemoveFeaturedImageFromCustomPostMutationResolver()
                : $this->getRemoveFeaturedImageFromCustomPostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMediaMutations = $moduleConfiguration->usePayloadableCustomPostMediaMutations();
        if ($usePayloadableCustomPostMediaMutations) {
            return match ($fieldName) {
                'setFeaturedImage' => $this->getCustomPostSetFeaturedImageMutationPayloadObjectTypeResolver(),
                'removeFeaturedImage' => $this->getCustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'setFeaturedImage',
            'removeFeaturedImage'
                => $this->getCustomPostUnionTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
