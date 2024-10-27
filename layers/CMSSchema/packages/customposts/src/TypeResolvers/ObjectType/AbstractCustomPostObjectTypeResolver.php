<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

abstract class AbstractCustomPostObjectTypeResolver extends AbstractObjectTypeResolver implements CustomPostObjectTypeResolverInterface
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a custom post', 'customposts');
    }

    public function getID(object $object): string|int|null
    {
        return $this->getCustomPostTypeAPI()->getID($object);
    }
}
