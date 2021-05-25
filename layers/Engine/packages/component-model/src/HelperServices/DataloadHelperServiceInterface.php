<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface DataloadHelperServiceInterface
{
    public function getTypeResolverClassFromSubcomponentDataField(TypeResolverInterface $typeResolver, string $subcomponent_data_field): ?string;

    /**
     * @param array<array<string, mixed>> $moduleValues
     */
    public function addFilterParams(string $url, array $moduleValues = []): string;
}
