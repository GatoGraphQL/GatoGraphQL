<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverPickers;

use PoP\ComponentModel\TypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolverPickers\CastableTypeResolverPickerInterface;

abstract class AbstractCastableTypeResolverPicker extends AbstractObjectTypeResolverPicker implements CastableTypeResolverPickerInterface
{
    public function cast(object $resultItem)
    {
        return $resultItem;
    }
}
