<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMedia\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMedia\FieldResolvers\InterfaceType\SupportingFeaturedImageInterfaceTypeFieldResolver;
use PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;

abstract class AbstractSupportingFeaturedImageCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MaybeSupportingFeaturedImageCustomPostObjectTypeFieldResolverTrait;

    private ?CustomPostMediaTypeAPIInterface $customPostMediaTypeAPI = null;
    private ?SupportingFeaturedImageInterfaceTypeFieldResolver $supportingFeaturedImageInterfaceTypeFieldResolver = null;

    final public function setCustomPostMediaTypeAPI(CustomPostMediaTypeAPIInterface $customPostMediaTypeAPI): void
    {
        $this->customPostMediaTypeAPI = $customPostMediaTypeAPI;
    }
    final protected function getCustomPostMediaTypeAPI(): CustomPostMediaTypeAPIInterface
    {
        /** @var CustomPostMediaTypeAPIInterface */
        return $this->customPostMediaTypeAPI ??= $this->instanceManager->getInstance(CustomPostMediaTypeAPIInterface::class);
    }
    final public function setSupportingFeaturedImageInterfaceTypeFieldResolver(SupportingFeaturedImageInterfaceTypeFieldResolver $supportingFeaturedImageInterfaceTypeFieldResolver): void
    {
        $this->supportingFeaturedImageInterfaceTypeFieldResolver = $supportingFeaturedImageInterfaceTypeFieldResolver;
    }
    final protected function getSupportingFeaturedImageInterfaceTypeFieldResolver(): SupportingFeaturedImageInterfaceTypeFieldResolver
    {
        /** @var SupportingFeaturedImageInterfaceTypeFieldResolver */
        return $this->supportingFeaturedImageInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(SupportingFeaturedImageInterfaceTypeFieldResolver::class);
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getSupportingFeaturedImageInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'hasFeaturedImage',
            'featuredImage',
        ];
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $customPost = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'hasFeaturedImage':
                return $this->getCustomPostMediaTypeAPI()->hasCustomPostThumbnail($customPost);

            case 'featuredImage':
                return $this->getCustomPostMediaTypeAPI()->getCustomPostThumbnailID($customPost);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
