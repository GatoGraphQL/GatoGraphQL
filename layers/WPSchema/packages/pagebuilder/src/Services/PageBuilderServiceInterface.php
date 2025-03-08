<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\Services;

use PoPWPSchema\PageBuilder\PageBuilderProviders\PageBuilderProviderInterface;

interface PageBuilderInterface
{
    /**
     * @return array<string,PageBuilderProviderInterface>
     */
    public function getProviderNameServices(): array;
}
