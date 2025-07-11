<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\EnumType;

use PoPCMSSchema\Taxonomies\Module;
use PoPCMSSchema\Taxonomies\ModuleConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;
use PoP\ComponentModel\App;

abstract class AbstractTaxonomyTermTaxonomyEnumStringScalarTypeResolver extends AbstractEnumStringScalarTypeResolver
{
    public function getTypeDescription(): string
    {
        return sprintf(
            $this->__('TaxonomyTerm taxonomies (available for querying via the API), with possible values: `"%s"`.', 'taxonomies'),
            implode('"`, `"', $this->getConsolidatedPossibleValues())
        );
    }

    /**
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        // @todo Add config for this and fix
        // /** @var ModuleConfiguration */
        // $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        // $queryableTaxonomyTermTaxonomies = $moduleConfiguration->getQueryableTaxonomyTermTaxonomies();

        // // If `null` => it's the "generic" resolver
        // $registeredCustomPostTaxonomyTermTaxonomyNames = $this->getRegisteredCustomPostTaxonomyTermTaxonomyNames();
        // if ($registeredCustomPostTaxonomyTermTaxonomyNames === null) {
        //     return $queryableTaxonomyTermTaxonomies;
        // }

        // return array_values(array_intersect(
        //     $registeredCustomPostTaxonomyTermTaxonomyNames,
        //     $queryableTaxonomyTermTaxonomies
        // ));

        return $this->getRegisteredCustomPostTaxonomyTermTaxonomyNames() ?? [];
    }

    /**
     * Return all the taxonomyTerm taxonomies registered for the custom post type.
     *
     * @return string[]|null
     */
    abstract protected function getRegisteredCustomPostTaxonomyTermTaxonomyNames(): ?array;
}
