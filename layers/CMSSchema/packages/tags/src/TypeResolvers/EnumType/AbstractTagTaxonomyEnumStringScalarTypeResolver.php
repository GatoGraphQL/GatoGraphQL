<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\EnumType;

use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;
use PoP\ComponentModel\App;

abstract class AbstractTagTaxonomyEnumStringScalarTypeResolver extends AbstractEnumStringScalarTypeResolver
{
    public function getTypeDescription(): string
    {
        return sprintf(
            $this->__('Tag taxonomies (available for querying via the API), with possible values: `"%s"`.', 'tags'),
            implode('"`, `"', $this->getConsolidatedPossibleValues())
        );
    }

    /**
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $queryableTagTaxonomies = $moduleConfiguration->getQueryableTagTaxonomies();

        // If `null` => it's the "generic" resolver
        $registeredCustomPostTagTaxonomyNames = $this->getRegisteredCustomPostTagTaxonomyNames();
        if ($registeredCustomPostTagTaxonomyNames === null) {
            return $queryableTagTaxonomies;
        }

        return array_values(array_intersect(
            $registeredCustomPostTagTaxonomyNames,
            $queryableTagTaxonomies
        ));
    }

    /**
     * Return all the tag taxonomies registered for the custom post type.
     *
     * @return string[]|null
     */
    abstract protected function getRegisteredCustomPostTagTaxonomyNames(): ?array;
}
