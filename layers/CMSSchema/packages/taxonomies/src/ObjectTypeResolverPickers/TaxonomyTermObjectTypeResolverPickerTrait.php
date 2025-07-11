<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\ObjectTypeResolverPickers;

use PoPCMSSchema\Taxonomies\Module;
use PoPCMSSchema\Taxonomies\ModuleConfiguration;
use PoP\ComponentModel\App;

trait TaxonomyTermObjectTypeResolverPickerTrait
{
    /**
     * @return string[]
     */
    abstract public function getTaxonomyTermTaxonomies(): array;

    public function isServiceEnabled(): bool
    {
        // @todo Add config for this and fix
        return true;
        // /** @var ModuleConfiguration */
        // $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        // return array_intersect(
        //     $this->getTaxonomyTermTaxonomies(),
        //     $moduleConfiguration->getQueryableTaxonomyTermTaxonomies()
        // ) !== [];
    }
}
