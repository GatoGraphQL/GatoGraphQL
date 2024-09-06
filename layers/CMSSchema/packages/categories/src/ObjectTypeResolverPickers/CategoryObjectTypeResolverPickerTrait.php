<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ObjectTypeResolverPickers;

use PoPCMSSchema\Categories\Module;
use PoPCMSSchema\Categories\ModuleConfiguration;
use PoP\ComponentModel\App;

trait CategoryObjectTypeResolverPickerTrait
{
    /**
     * @return string[]
     */
    abstract public function getCategoryTaxonomies(): array;

    public function isServiceEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return array_intersect(
            $this->getCategoryTaxonomies(),
            $moduleConfiguration->getQueryableCategoryTaxonomies()
        ) !== [];
    }
}
