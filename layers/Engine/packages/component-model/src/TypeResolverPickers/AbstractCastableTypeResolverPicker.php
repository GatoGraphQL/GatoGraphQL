<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverPickers;

use PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker;
use PoP\ComponentModel\TypeResolverPickers\CastableTypeResolverPickerInterface;

abstract class AbstractCastableTypeResolverPicker extends AbstractTypeResolverPicker implements CastableTypeResolverPickerInterface
{
    public function cast(object $resultItem)
    {
        return $resultItem;
    }
}
