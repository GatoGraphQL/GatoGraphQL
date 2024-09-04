<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ObjectTypeResolverPickers;

use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoP\ComponentModel\App;

trait TagObjectTypeResolverPickerTrait
{
    /**
     * @return string[]
     */
    abstract public function getTagTaxonomies(): array;

    public function isServiceEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return in_array(
            $this->getTagTaxonomies(),
            $moduleConfiguration->getQueryableTagTaxonomies()
        );
    }
}
