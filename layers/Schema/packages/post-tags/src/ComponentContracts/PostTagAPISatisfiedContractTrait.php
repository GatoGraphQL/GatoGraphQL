<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ComponentContracts;

use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;

trait PostTagAPISatisfiedContractTrait
{
    protected function getTypeAPI(): \PoPSchema\Tags\FunctionAPI
    {
        $cmstagsapi = \PoPSchema\PostTags\FunctionAPIFactory::getInstance();
        return $cmstagsapi;
    }

    protected function getTypeResolverClass(): string
    {
        return PostTagTypeResolver::class;
    }
}
