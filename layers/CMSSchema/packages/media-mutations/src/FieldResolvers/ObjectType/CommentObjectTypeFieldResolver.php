<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MediaMutations\Module;
use PoPCMSSchema\MediaMutations\ModuleConfiguration;
use PoPCMSSchema\MediaMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPCMSSchema\MediaMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType\CommentReplyInputObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\CommentReplyMutationPayloadObjectTypeResolver;
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

class CommentObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver = null;
    private ?CommentReplyInputObjectTypeResolver $commentReplyInputObjectTypeResolver = null;
    private ?CommentReplyMutationPayloadObjectTypeResolver $commentReplyMutationPayloadObjectTypeResolver = null;
    private ?PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver = null;

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
    final public function setCommentReplyInputObjectTypeResolver(CommentReplyInputObjectTypeResolver $commentReplyInputObjectTypeResolver): void
    {
        $this->commentReplyInputObjectTypeResolver = $commentReplyInputObjectTypeResolver;
    }
    final protected function getCommentReplyInputObjectTypeResolver(): CommentReplyInputObjectTypeResolver
    {
        if ($this->commentReplyInputObjectTypeResolver === null) {
            /** @var CommentReplyInputObjectTypeResolver */
            $commentReplyInputObjectTypeResolver = $this->instanceManager->getInstance(CommentReplyInputObjectTypeResolver::class);
            $this->commentReplyInputObjectTypeResolver = $commentReplyInputObjectTypeResolver;
        }
        return $this->commentReplyInputObjectTypeResolver;
    }
    final public function setCommentReplyMutationPayloadObjectTypeResolver(CommentReplyMutationPayloadObjectTypeResolver $commentReplyMutationPayloadObjectTypeResolver): void
    {
        $this->commentReplyMutationPayloadObjectTypeResolver = $commentReplyMutationPayloadObjectTypeResolver;
    }
    final protected function getCommentReplyMutationPayloadObjectTypeResolver(): CommentReplyMutationPayloadObjectTypeResolver
    {
        if ($this->commentReplyMutationPayloadObjectTypeResolver === null) {
            /** @var CommentReplyMutationPayloadObjectTypeResolver */
            $commentReplyMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(CommentReplyMutationPayloadObjectTypeResolver::class);
            $this->commentReplyMutationPayloadObjectTypeResolver = $commentReplyMutationPayloadObjectTypeResolver;
        }
        return $this->commentReplyMutationPayloadObjectTypeResolver;
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

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'reply',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'reply' => $this->__('Reply a comment with another comment', 'media-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
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
            'reply' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'reply' => [
                MutationInputProperties::INPUT => $this->getCommentReplyInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['reply' => MutationInputProperties::INPUT] => SchemaTypeModifiers::MANDATORY,
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
            'reply' => true,
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
        $comment = $object;
        switch ($field->getName()) {
            case 'reply':
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::CUSTOMPOST_ID} = $this->getMediaTypeAPI()->getCommentPostID($comment);
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::PARENT_COMMENT_ID} = $objectTypeResolver->getID($comment);
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
            'reply' => $usePayloadableMediaMutations
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
            'reply' => $usePayloadableMediaMutations
                ? $this->getCommentReplyMutationPayloadObjectTypeResolver()
                : $this->getCommentObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
