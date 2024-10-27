<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMediaMutations\ConditionalOnModule\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType\AbstractWithFeaturedImageCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class WithFeaturedImagePostObjectTypeFieldResolver extends AbstractWithFeaturedImageCustomPostObjectTypeFieldResolver
{
    private ?PostTypeAPIInterface $postTypeAPI = null;

    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        if ($this->postTypeAPI === null) {
            /** @var PostTypeAPIInterface */
            $postTypeAPI = $this->instanceManager->getInstance(PostTypeAPIInterface::class);
            $this->postTypeAPI = $postTypeAPI;
        }
        return $this->postTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostObjectTypeResolver::class,
        ];
    }

    protected function getCustomPostType(): string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'setFeaturedImage' => $this->__('Set the featured image on the post', 'postmedia-mutations'),
            'removeFeaturedImage' => $this->__('Remove the featured image on the post', 'postmedia-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }
}
