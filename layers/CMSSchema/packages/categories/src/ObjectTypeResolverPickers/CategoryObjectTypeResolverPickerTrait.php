<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ObjectTypeResolverPickers;

use PoPCMSSchema\Categories\Module;
use PoPCMSSchema\Categories\ModuleConfiguration;
use PoP\ComponentModel\App;

trait CategoryObjectTypeResolverPickerTrait
{
    abstract public function getCategoryTaxonomy(): string;

    public function isServiceEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return in_array(
            $this->getCategoryTaxonomy(),
            $moduleConfiguration->getQueryableCategoryTaxonomies()
        );
    }
}
