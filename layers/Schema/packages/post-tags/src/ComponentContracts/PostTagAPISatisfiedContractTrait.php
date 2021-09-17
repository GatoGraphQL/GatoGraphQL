<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\ComponentContracts;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;

trait PostTagAPISatisfiedContractTrait
{
    protected function getTagTypeAPI(): TagTypeAPIInterface
    {
        return PostTagTypeAPIFacade::getInstance();
    }

    protected function getTagTypeResolver(): TagObjectTypeResolverInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }
}
