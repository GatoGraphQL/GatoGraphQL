<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

use stdClass;

abstract class AbstractFixedTaxonomyFilterCustomPostsByTagsInputObjectTypeResolver extends AbstractFilterCustomPostsByTagsInputObjectTypeResolver
{
    protected function addTagTaxonomyFilterInput(): bool
    {
        return false;
    }

    abstract protected function getTagTaxonomyName(): string;

    /**
     * @param array<string,mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(array &$query, stdClass|array $inputValue): void
    {
        parent::integrateInputValueToFilteringQueryArgs($query, $inputValue);

        $query['tag-taxonomy'] = $this->getTagTaxonomyName();
    }
}
