<?php

declare(strict_types=1);

namespace PoPSchema\Stances\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Stances\Facades\StanceTypeAPIFacade;
use PoPSchema\Stances\TypeResolvers\ObjectType\StanceObjectTypeResolver;

abstract class AbstractStanceObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    private ?StanceObjectTypeResolver $stanceObjectTypeResolver = null;
    
    final public function setStanceObjectTypeResolver(StanceObjectTypeResolver $stanceObjectTypeResolver): void
    {
        $this->stanceObjectTypeResolver = $stanceObjectTypeResolver;
    }
    final protected function getStanceObjectTypeResolver(): StanceObjectTypeResolver
    {
        return $this->stanceObjectTypeResolver ??= $this->instanceManager->getInstance(StanceObjectTypeResolver::class);
    }
    
    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getStanceObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->isInstanceOfStanceType($object);
    }

    public function isIDOfType(string|int $objectID): bool
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->stanceExists($objectID);
    }
}
