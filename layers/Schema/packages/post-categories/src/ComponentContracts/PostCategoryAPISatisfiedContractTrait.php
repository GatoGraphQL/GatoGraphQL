<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ComponentContracts;

use PoPSchema\PostCategories\TypeResolvers\PostCategoryTypeResolver;

trait PostCategoryAPISatisfiedContractTrait
{
    protected function getTypeAPI(): \PoPSchema\Categories\FunctionAPI
    {
        $cmscategoriesapi = \PoPSchema\PostCategories\FunctionAPIFactory::getInstance();
        return $cmscategoriesapi;
    }

    protected function getTypeResolverClass(): string
    {
        return PostCategoryTypeResolver::class;
    }
}
