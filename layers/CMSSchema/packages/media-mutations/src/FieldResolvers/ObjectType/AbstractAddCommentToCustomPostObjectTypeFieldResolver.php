<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MediaMutations\Module;
use PoPCMSSchema\MediaMutations\ModuleConfiguration;
use PoPCMSSchema\MediaMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPCMSSchema\MediaMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType\CustomPostAddCommentInputObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\CustomPostAddCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Comments\FieldResolvers\ObjectType\MaybeCommentableCustomPostObjectTypeFieldResolverTrait;
use PoPCMSSchema\Comments\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractAddCommentToCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MaybeCommentableCustomPostObjectTypeFieldResolverTrait;

    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver = null;
    private ?CustomPostAddCommentInputObjectTypeResolver $customPostAddCommentInputObjectTypeResolver = null;
    private ?CustomPostAddCommentMutationPayloadObjectTypeResolver $customPostAddCommentMutationPayloadObjectTypeResolver = null;
    private ?PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver = null;
    private ?MediaTypeAPIInterface $mediaTypeAPI = null;

    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        if ($this->commentObjectTypeResolver === null) {
            /** @var CommentObjectTypeResolver */
            $commentObjectTypeResolver = $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
            $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        }
        return $this->commentObjectTypeResolver;
    }
    final public function setAddCommentToCustomPostMutationResolver(AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver): void
    {
        $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
    }
    final protected function getAddCommentToCustomPostMutationResolver(): AddCommentToCustomPostMutationResolver
    {
        if ($this->addCommentToCustomPostMutationResolver === null) {
            /** @var AddCommentToCustomPostMutationResolver */
            $addCommentToCustomPostMutationResolver = $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolver::class);
            $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
        }
        return $this->addCommentToCustomPostMutationResolver;
    }
    final public function setCustomPostAddCommentInputObjectTypeResolver(CustomPostAddCommentInputObjectTypeResolver $customPostAddCommentInputObjectTypeResolver): void
    {
        $this->customPostAddCommentInputObjectTypeResolver = $customPostAddCommentInputObjectTypeResolver;
    }
    final protected function getCustomPostAddCommentInputObjectTypeResolver(): CustomPostAddCommentInputObjectTypeResolver
    {
        if ($this->customPostAddCommentInputObjectTypeResolver === null) {
            /** @var CustomPostAddCommentInputObjectTypeResolver */
            $customPostAddCommentInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostAddCommentInputObjectTypeResolver::class);
            $this->customPostAddCommentInputObjectTypeResolver = $customPostAddCommentInputObjectTypeResolver;
        }
        return $this->customPostAddCommentInputObjectTypeResolver;
    }
    final public function setCustomPostAddCommentMutationPayloadObjectTypeResolver(CustomPostAddCommentMutationPayloadObjectTypeResolver $customPostAddCommentMutationPayloadObjectTypeResolver): void
    {
        $this->customPostAddCommentMutationPayloadObjectTypeResolver = $customPostAddCommentMutationPayloadObjectTypeResolver;
    }
    final protected function getCustomPostAddCommentMutationPayloadObjectTypeResolver(): CustomPostAddCommentMutationPayloadObjectTypeResolver
    {
        if ($this->customPostAddCommentMutationPayloadObjectTypeResolver === null) {
            /** @var CustomPostAddCommentMutationPayloadObjectTypeResolver */
            $customPostAddCommentMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(CustomPostAddCommentMutationPayloadObjectTypeResolver::class);
            $this->customPostAddCommentMutationPayloadObjectTypeResolver = $customPostAddCommentMutationPayloadObjectTypeResolver;
        }
        return $this->customPostAddCommentMutationPayloadObjectTypeResolver;
    }
    final public function setPayloadableAddCommentToCustomPostMutationResolver(PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver): void
    {
        $this->payloadableAddCommentToCustomPostMutationResolver = $payloadableAddCommentToCustomPostMutationResolver;
    }
    final protected function getPayloadableAddCommentToCustomPostMutationResolver(): PayloadableAddCommentToCustomPostMutationResolver
    {
        if ($this->payloadableAddCommentToCustomPostMutationResolver === null) {
            /** @var PayloadableAddCommentToCustomPostMutationResolver */
            $payloadableAddCommentToCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableAddCommentToCustomPostMutationResolver::class);
            $this->payloadableAddCommentToCustomPostMutationResolver = $payloadableAddCommentToCustomPostMutationResolver;
        }
        return $this->payloadableAddCommentToCustomPostMutationResolver;
    }
    final public function setMediaTypeAPI(MediaTypeAPIInterface $mediaTypeAPI): void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        if ($this->mediaTypeAPI === null) {
            /** @var MediaTypeAPIInterface */
            $mediaTypeAPI = $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
            $this->mediaTypeAPI = $mediaTypeAPI;
        }
        return $this->mediaTypeAPI;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'addComment',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addComment' => $this->getAddCommentFieldDescription(),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getAddCommentFieldDescription(): string
    {
        return $this->__('Add a comment to the custom post', 'media-mutations');
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableMediaMutations = $moduleConfiguration->usePayloadableMediaMutations();
        if (!$usePayloadableMediaMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        return match ($fieldName) {
            'addComment' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'addComment' => [
                MutationInputProperties::INPUT => $this->getCustomPostAddCommentInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['addComment' => MutationInputProperties::INPUT] => SchemaTypeModifiers::MANDATORY,
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
            'addComment' => true,
            default => parent::validateMutationOnObject($objectTypeResolver, $fieldName),
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
            case 'addComment':
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::CUSTOMPOST_ID} = $objectTypeResolver->getID($customPost);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableMediaMutations = $moduleConfiguration->usePayloadableMediaMutations();
        return match ($fieldName) {
            'addComment' => $usePayloadableMediaMutations
                ? $this->getPayloadableAddCommentToCustomPostMutationResolver()
                : $this->getAddCommentToCustomPostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableMediaMutations = $moduleConfiguration->usePayloadableMediaMutations();
        return match ($fieldName) {
            'addComment' => $usePayloadableMediaMutations
                ? $this->getCustomPostAddCommentMutationPayloadObjectTypeResolver()
                : $this->getCommentObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
