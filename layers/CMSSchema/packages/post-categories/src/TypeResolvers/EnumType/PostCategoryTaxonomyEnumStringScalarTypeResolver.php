<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\TypeResolvers\EnumType;

use PoPCMSSchema\Categories\TypeResolvers\EnumType\AbstractCategoryTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

class PostCategoryTaxonomyEnumStringScalarTypeResolver extends AbstractCategoryTaxonomyEnumStringScalarTypeResolver
{
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        if ($this->postCategoryTypeAPI === null) {
            /** @var PostCategoryTypeAPIInterface */
            $postCategoryTypeAPI = $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
            $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        }
        return $this->postCategoryTypeAPI;
    }

    public function getTypeName(): string
    {
        return 'PostCategoryTaxonomyEnumString';
    }

    public function getEnumStringTypeDescription(): ?string
    {
        return $this->__('Post category taxonomies (available for querying via the API)', 'categories');
    }

    protected function getRegisteredCustomPostCategoryTaxonomyNames(): ?array
    {
        return $this->getPostCategoryTypeAPI()->getRegisteredPostCategoryTaxonomyNames();
    }
}
