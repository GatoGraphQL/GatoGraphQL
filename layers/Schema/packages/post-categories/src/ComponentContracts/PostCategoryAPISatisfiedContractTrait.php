<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ComponentContracts;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;

trait PostCategoryAPISatisfiedContractTrait
{
    public function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return PostCategoryTypeAPIFacade::getInstance();
    }

    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }
}
