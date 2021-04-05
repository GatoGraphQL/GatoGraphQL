<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ComponentContracts;

use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;

trait PostTagAPISatisfiedContractTrait
{
    protected function getTypeAPI(): TagTypeAPIInterface
    {
        $cmstagsapi = \PoPSchema\PostTags\FunctionAPIFactory::getInstance();
        return $cmstagsapi;
    }

    protected function getTypeResolverClass(): string
    {
        return PostTagTypeResolver::class;
    }
}
