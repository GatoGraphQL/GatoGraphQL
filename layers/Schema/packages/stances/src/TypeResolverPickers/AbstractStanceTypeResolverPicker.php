<?php

declare(strict_types=1);

namespace PoPSchema\Stances\TypeResolverPickers;

use PoPSchema\Stances\Facades\StanceTypeAPIFacade;
use PoPSchema\Stances\TypeResolvers\StanceTypeResolver;
use PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker;

class AbstractStanceTypeResolverPicker extends AbstractTypeResolverPicker
{
    public function getTypeResolverClass(): string
    {
        return StanceTypeResolver::class;
    }

    public function isInstanceOfType($object): bool
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->isInstanceOfStanceType($object);
    }

    public function isIDOfType($resultItemID): bool
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->stanceExists($resultItemID);
    }
}
