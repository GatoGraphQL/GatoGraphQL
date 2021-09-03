<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

interface DataloadHelperServiceInterface
{
    public function getTypeResolverClassFromSubcomponentDataField(ObjectTypeResolverInterface $typeResolver, string $subcomponent_data_field): ?string;

    /**
     * @param array<array<string, mixed>> $moduleValues
     */
    public function addFilterParams(string $url, array $moduleValues = []): string;
}
