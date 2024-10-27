<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Categories\FieldResolvers\ObjectType\AbstractCustomPostListCategoryObjectTypeFieldResolver;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;

class CustomPostListPostCategoryObjectTypeFieldResolver extends AbstractCustomPostListCategoryObjectTypeFieldResolver
{
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;
    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;

    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        if ($this->postCategoryTypeAPI === null) {
            /** @var PostCategoryTypeAPIInterface */
            $postCategoryTypeAPI = $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
            $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        }
        return $this->postCategoryTypeAPI;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        if ($this->postCategoryObjectTypeResolver === null) {
            /** @var PostCategoryObjectTypeResolver */
            $postCategoryObjectTypeResolver = $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
            $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        }
        return $this->postCategoryObjectTypeResolver;
    }

    public function isServiceEnabled(): bool
    {
        /**
         * @todo Enable if the post category (i.e. taxonomy "category") can have other custom post types use it (eg: page, event, etc)
         */
        return false;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostCategoryObjectTypeResolver::class,
        ];
    }

    protected function getQueryProperty(): string
    {
        return 'category-ids';
    }

    public function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }

    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->getPostCategoryObjectTypeResolver();
    }
}
