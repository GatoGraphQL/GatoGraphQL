<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\HelperServices;

interface DataloadHelperServiceInterface
{
    /**
     * @param array<array<string, mixed>> $componentVariationValues
     */
    public function addFilterParams(string $url, array $componentVariationValues = []): string;
}
