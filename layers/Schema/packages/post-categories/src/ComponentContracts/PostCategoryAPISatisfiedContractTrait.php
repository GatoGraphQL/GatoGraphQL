<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\ComponentContracts;

use PoPSchema\PostCategories\TypeResolvers\PostCategoryTypeResolver;

trait PostCategoryAPISatisfiedContractTrait
{
    protected function getTypeAPI(): \PoPSchema\Categories\FunctionAPI
    {
        $cmstagsapi = \PoPSchema\PostCategories\FunctionAPIFactory::getInstance();
        return $cmstagsapi;
    }

    protected function getTypeResolverClass(): string
    {
        return PostCategoryTypeResolver::class;
    }
}
