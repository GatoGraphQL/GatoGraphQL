<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ComponentContracts;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait PostCategoryAPISatisfiedContractTrait
{
    protected PostCategoryTypeAPIInterface $postCategoryTypeAPI;
    protected PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver;

    #[Required]
    public function autowirePostCategoryAPISatisfiedContractTrait(
        PostCategoryTypeAPIInterface $postCategoryTypeAPI,
        PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver,
    ): void {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }

    public function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->postCategoryTypeAPI;
    }

    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->postCategoryObjectTypeResolver;
    }
}
