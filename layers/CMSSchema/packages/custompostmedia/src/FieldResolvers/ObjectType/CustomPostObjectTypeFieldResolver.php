<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMedia\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMedia\FieldResolvers\InterfaceType\SupportingFeaturedImageInterfaceTypeFieldResolver;
use PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;

class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?CustomPostMediaTypeAPIInterface $customPostMediaTypeAPI = null;
    private ?SupportingFeaturedImageInterfaceTypeFieldResolver $supportingFeaturedImageInterfaceTypeFieldResolver = null;

    final public function setCustomPostMediaTypeAPI(CustomPostMediaTypeAPIInterface $customPostMediaTypeAPI): void
    {
        $this->customPostMediaTypeAPI = $customPostMediaTypeAPI;
    }
    final protected function getCustomPostMediaTypeAPI(): CustomPostMediaTypeAPIInterface
    {
        return $this->customPostMediaTypeAPI ??= $this->instanceManager->getInstance(CustomPostMediaTypeAPIInterface::class);
    }
    final public function setSupportingFeaturedImageInterfaceTypeFieldResolver(SupportingFeaturedImageInterfaceTypeFieldResolver $supportingFeaturedImageInterfaceTypeFieldResolver): void
    {
        $this->supportingFeaturedImageInterfaceTypeFieldResolver = $supportingFeaturedImageInterfaceTypeFieldResolver;
    }
    final protected function getSupportingFeaturedImageInterfaceTypeFieldResolver(): SupportingFeaturedImageInterfaceTypeFieldResolver
    {
        return $this->supportingFeaturedImageInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(SupportingFeaturedImageInterfaceTypeFieldResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getSupportingFeaturedImageInterfaceTypeFieldResolver(),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'hasFeaturedImage',
            'featuredImage',
        ];
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
        array $options = []
    ): mixed {
        $post = $object;
        switch ($fieldName) {
            case 'hasFeaturedImage':
                return $this->getCustomPostMediaTypeAPI()->hasCustomPostThumbnail($objectTypeResolver->getID($post));

            case 'featuredImage':
                return $this->getCustomPostMediaTypeAPI()->getCustomPostThumbnailID($objectTypeResolver->getID($post));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}
