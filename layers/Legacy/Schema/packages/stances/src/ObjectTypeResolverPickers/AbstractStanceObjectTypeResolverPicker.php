<?php

declare(strict_types=1);

namespace PoPSchema\Stances\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Stances\Facades\StanceTypeAPIFacade;
use PoPSchema\Stances\TypeResolvers\ObjectType\StanceObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractStanceObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    private ?StanceObjectTypeResolver $stanceObjectTypeResolver = null;
    
    public function setStanceObjectTypeResolver(StanceObjectTypeResolver $stanceObjectTypeResolver): void
    {
        $this->stanceObjectTypeResolver = $stanceObjectTypeResolver;
    }
    protected function getStanceObjectTypeResolver(): StanceObjectTypeResolver
    {
        return $this->stanceObjectTypeResolver ??= $this->getInstanceManager()->getInstance(StanceObjectTypeResolver::class);
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

    public function isIDOfType(string | int $objectID): bool
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->stanceExists($objectID);
    }
}
