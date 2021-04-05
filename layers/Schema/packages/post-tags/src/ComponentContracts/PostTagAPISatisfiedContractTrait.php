<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ComponentContracts;

use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

trait PostTagAPISatisfiedContractTrait
{
    protected function getTypeAPI(): TagTypeAPIInterface
    {
        return PostTagTypeAPIFacade::getInstance();
    }

    protected function getTypeResolverClass(): string
    {
        return PostTagTypeResolver::class;
    }
}
