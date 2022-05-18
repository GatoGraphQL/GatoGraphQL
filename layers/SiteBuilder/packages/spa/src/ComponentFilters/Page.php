<?php

declare(strict_types=1);

namespace PoP\SPA\ComponentFilters;

use PoP\ComponentModel\ComponentFilters\AbstractComponentFilter;
use PoP\SPA\Modules\PageInterface;

class Page extends AbstractComponentFilter
{
    public function getName(): string
    {
        return 'page';
    }

    public function excludeModule(array $module, array &$props): bool
    {

        // Exclude until reaching the pageSection
        $processor = $this->getComponentProcessorManager()->getProcessor($module);
        return !($processor instanceof PageInterface);
    }
}
