<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ObjectTypeResolverPickers;

use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoP\ComponentModel\App;

trait TagObjectTypeResolverPickerTrait
{
    abstract public function getTagTaxonomy(): string;

    public function isServiceEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return in_array(
            $this->getTagTaxonomy(),
            $moduleConfiguration->getQueryableTagTaxonomies()
        );
    }
}
