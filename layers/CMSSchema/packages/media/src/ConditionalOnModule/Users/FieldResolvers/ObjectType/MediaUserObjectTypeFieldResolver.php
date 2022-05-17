<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\ConditionalOnModule\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Media\ConditionalOnModule\Users\TypeAPIs\UserMediaTypeAPIInterface;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class MediaUserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?UserMediaTypeAPIInterface $userMediaTypeAPI = null;
    private ?UserObjectTypeResolver $userObjectTypeResolver = null;

    final public function setUserMediaTypeAPI(UserMediaTypeAPIInterface $userMediaTypeAPI): void
    {
        $this->userMediaTypeAPI = $userMediaTypeAPI;
    }
    final protected function getUserMediaTypeAPI(): UserMediaTypeAPIInterface
    {
        return $this->userMediaTypeAPI ??= $this->instanceManager->getInstance(UserMediaTypeAPIInterface::class);
    }
    final public function setUserObjectTypeResolver(UserObjectTypeResolver $userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        return $this->userObjectTypeResolver ??= $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MediaObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'author',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'author' => $this->__('Media element\'s author', 'pop-media'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $media = $object;
        return match ($fieldName) {
            'author' => $this->getUserMediaTypeAPI()->getMediaAuthorId($media),
            default => parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'author' => $this->getUserObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
