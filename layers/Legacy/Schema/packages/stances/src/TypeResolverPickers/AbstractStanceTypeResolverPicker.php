<?php

declare(strict_types=1);

namespace PoPSchema\Stances\TypeResolverPickers;

use PoPSchema\Stances\Facades\StanceTypeAPIFacade;
use PoPSchema\Stances\TypeResolvers\Object\StanceTypeResolver;
use PoP\ComponentModel\TypeResolverPickers\AbstractObjectTypeResolverPicker;

class AbstractStanceTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    public function getObjectTypeResolverClass(): string
    {
        return StanceTypeResolver::class;
    }

    public function isInstanceOfType(object $object): bool
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->isInstanceOfStanceType($object);
    }

    public function isIDOfType(string | int $resultItemID): bool
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->stanceExists($resultItemID);
    }
}
