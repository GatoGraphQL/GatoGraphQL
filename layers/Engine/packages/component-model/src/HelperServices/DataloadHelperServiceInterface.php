<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface DataloadHelperServiceInterface
{
    public function getTypeResolverClassFromSubcomponentDataField(TypeResolverInterface $typeResolver, $subcomponent_data_field);

    public function addFilterParams($url, $moduleValues = array());
}
