<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface DataloadHelperServiceInterface
{
    public function getTypeResolverFromSubcomponentDataField(RelationalTypeResolverInterface $relationalTypeResolver, string $subcomponent_data_field): ?RelationalTypeResolverInterface;

    /**
     * @param array<array<string, mixed>> $componentVariationValues
     */
    public function addFilterParams(string $url, array $componentVariationValues = []): string;
}
