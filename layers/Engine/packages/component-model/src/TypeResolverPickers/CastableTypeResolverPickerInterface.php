<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverPickers;

interface CastableTypeResolverPickerInterface
{
    public function cast(object $resultItem);
}
