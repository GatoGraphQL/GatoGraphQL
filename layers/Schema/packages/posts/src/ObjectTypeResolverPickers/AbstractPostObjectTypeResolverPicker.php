<?php

declare(strict_types=1);

namespace PoPSchema\Posts\ObjectTypeResolverPickers;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

abstract class AbstractPostObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    protected PostObjectTypeResolver $postObjectTypeResolver;
    protected PostTypeAPIInterface $postTypeAPI;

    #[Required]
    public function autowireAbstractPostObjectTypeResolverPicker(PostObjectTypeResolver $postObjectTypeResolver, PostTypeAPIInterface $postTypeAPI)
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
        $this->postTypeAPI = $postTypeAPI;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->postObjectTypeResolver;
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->postTypeAPI->isInstanceOfPostType($object);
    }

    public function isIDOfType(string | int $objectID): bool
    {
        return $this->postTypeAPI->postExists($objectID);
    }
}
