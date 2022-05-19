<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\HelperServices;

interface DataloadHelperServiceInterface
{
    /**
     * @param array<array<string, mixed>> $componentValues
     */
    public function addFilterParams(string $url, array $componentValues = []): string;
}
