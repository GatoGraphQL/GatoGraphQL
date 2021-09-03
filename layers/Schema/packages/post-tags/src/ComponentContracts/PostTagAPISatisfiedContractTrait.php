<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ComponentContracts;

use PoPSchema\PostTags\TypeResolvers\Object\PostTagTypeResolver;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

trait PostTagAPISatisfiedContractTrait
{
    protected function getTagTypeAPI(): TagTypeAPIInterface
    {
        return PostTagTypeAPIFacade::getInstance();
    }

    protected function getTagTypeResolverClass(): string
    {
        return PostTagTypeResolver::class;
    }
}
