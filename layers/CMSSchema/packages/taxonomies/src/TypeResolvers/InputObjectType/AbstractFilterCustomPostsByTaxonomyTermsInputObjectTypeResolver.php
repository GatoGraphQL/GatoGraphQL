<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\FilterByTaxonomyTermsInputObjectTypeResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use stdClass;

abstract class AbstractFilterCustomPostsByTaxonomyTermsInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver implements FilterCustomPostsByTaxonomyTermsInputObjectTypeResolverInterface
{
    private ?FilterByTaxonomyTermsInputObjectTypeResolver $filterByTaxonomyTermsInputObjectTypeResolver = null;

    final protected function getFilterByTaxonomyTermsInputObjectTypeResolver(): FilterByTaxonomyTermsInputObjectTypeResolver
    {
        if ($this->filterByTaxonomyTermsInputObjectTypeResolver === null) {
            /** @var FilterByTaxonomyTermsInputObjectTypeResolver */
            $filterByTaxonomyTermsInputObjectTypeResolver = $this->instanceManager->getInstance(FilterByTaxonomyTermsInputObjectTypeResolver::class);
            $this->filterByTaxonomyTermsInputObjectTypeResolver = $filterByTaxonomyTermsInputObjectTypeResolver;
        }
        return $this->filterByTaxonomyTermsInputObjectTypeResolver;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter custom posts by taxonomy terms', 'taxonomies');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'taxonomy' => $this->getTaxonomyTermTaxonomyFilterInput(),
            'includeBy' => $this->getFilterByTaxonomyTermsInputObjectTypeResolver(),
            'excludeBy' => $this->getFilterByTaxonomyTermsInputObjectTypeResolver(),
        ];
    }

    abstract protected function getTaxonomyTermTaxonomyFilterInput(): InputTypeResolverInterface;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'taxonomy' => $this->__('TaxonomyTerm taxonomy', 'taxonomies'),
            'includeBy' => $this->__('Retrieve custom posts which contain taxonomy terms', 'taxonomies'),
            'excludeBy' => $this->__('Retrieve custom posts which do not contain taxonomy terms', 'taxonomies'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'taxonomy' => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'taxonomy' => $this->getTaxonomyTermTaxonomyFilterDefaultValue(),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    abstract protected function getTaxonomyTermTaxonomyFilterDefaultValue(): mixed;

    /**
     * @param array<string,mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(array &$query, stdClass|array $inputValue): void
    {
        parent::integrateInputValueToFilteringQueryArgs($query, $inputValue);

        if (isset($inputValue->taxonomy)) {
            $query['taxonomyTerm-taxonomy'] = $inputValue->taxonomy;
        }

        if (isset($inputValue->includeBy)) {
            if (isset($inputValue->includeBy->ids)) {
                $query['taxonomyTerm-ids'] = $inputValue->includeBy->ids;
            }
            if (isset($inputValue->includeBy->slugs)) {
                $query['taxonomyTerm-slugs'] = $inputValue->includeBy->slugs;
            }
        }

        if (isset($inputValue->excludeBy)) {
            if (isset($inputValue->excludeBy->ids)) {
                $query['exclude-taxonomyTerm-ids'] = $inputValue->excludeBy->ids;
            }
            if (isset($inputValue->excludeBy->slugs)) {
                $query['exclude-taxonomyTerm-slugs'] = $inputValue->excludeBy->slugs;
            }
        }
    }
}
