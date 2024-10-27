<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMedia\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMedia\FieldResolvers\InterfaceType\WithFeaturedImageInterfaceTypeFieldResolver;
use PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractWithFeaturedImageCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MaybeWithFeaturedImageCustomPostObjectTypeFieldResolverTrait;

    private ?CustomPostMediaTypeAPIInterface $customPostMediaTypeAPI = null;
    private ?WithFeaturedImageInterfaceTypeFieldResolver $withFeaturedImageInterfaceTypeFieldResolver = null;

    final protected function getCustomPostMediaTypeAPI(): CustomPostMediaTypeAPIInterface
    {
        if ($this->customPostMediaTypeAPI === null) {
            /** @var CustomPostMediaTypeAPIInterface */
            $customPostMediaTypeAPI = $this->instanceManager->getInstance(CustomPostMediaTypeAPIInterface::class);
            $this->customPostMediaTypeAPI = $customPostMediaTypeAPI;
        }
        return $this->customPostMediaTypeAPI;
    }
    final protected function getWithFeaturedImageInterfaceTypeFieldResolver(): WithFeaturedImageInterfaceTypeFieldResolver
    {
        if ($this->withFeaturedImageInterfaceTypeFieldResolver === null) {
            /** @var WithFeaturedImageInterfaceTypeFieldResolver */
            $withFeaturedImageInterfaceTypeFieldResolver = $this->instanceManager->getInstance(WithFeaturedImageInterfaceTypeFieldResolver::class);
            $this->withFeaturedImageInterfaceTypeFieldResolver = $withFeaturedImageInterfaceTypeFieldResolver;
        }
        return $this->withFeaturedImageInterfaceTypeFieldResolver;
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getWithFeaturedImageInterfaceTypeFieldResolver(),
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

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
