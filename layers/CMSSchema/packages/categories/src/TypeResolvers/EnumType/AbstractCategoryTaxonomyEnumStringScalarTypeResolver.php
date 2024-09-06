<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\EnumType;

use PoPCMSSchema\Categories\Module;
use PoPCMSSchema\Categories\ModuleConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;
use PoP\ComponentModel\App;

abstract class AbstractCategoryTaxonomyEnumStringScalarTypeResolver extends AbstractEnumStringScalarTypeResolver
{
    public function getTypeDescription(): string
    {
        return sprintf(
            $this->__('Category taxonomies (available for querying via the API), with possible values: `"%s"`.', 'categories'),
            implode('"`, `"', $this->getConsolidatedPossibleValues())
        );
    }

    /**
     * Return all the category taxonomies registered for the "post"
     * custom post type, that have also been selected as "queryable"
     * in the plugin settings.
     *
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $queryableCategoryTaxonomies = $moduleConfiguration->getQueryableCategoryTaxonomies();

        // If `null` => it's the "generic" resolver
        $registeredCustomPostCategoryTaxonomyNames = $this->getRegisteredCustomPostCategoryTaxonomyNames();
        if ($registeredCustomPostCategoryTaxonomyNames === null) {
            return $queryableCategoryTaxonomies;
        }

        return array_values(array_intersect(
            $registeredCustomPostCategoryTaxonomyNames,
            $queryableCategoryTaxonomies
        ));
    }

    /**
     * Return all the category taxonomies registered for the custom post type.
     *
     * @return string[]|null
     */
    abstract protected function getRegisteredCustomPostCategoryTaxonomyNames(): ?array;
}
