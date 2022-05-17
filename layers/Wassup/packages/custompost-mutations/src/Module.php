<?php

declare(strict_types=1);

namespace PoPSitesWassup\CustomPostMutations;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPostMutations\Module::class,
        ];
    }
}
