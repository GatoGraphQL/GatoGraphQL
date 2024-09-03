<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\StandaloneTypeAPIs;

use PoPCMSSchema\TagsWP\TypeAPIs\AbstractTagTypeAPI;

final class InjectableTaxonomyTagTypeAPI extends AbstractTagTypeAPI
{
    public function __construct(
        protected string $tagTaxonomy,
    ) {
    }

    /**
     * @return string[]
     */
    protected function getTagTaxonomyNames(): array
    {
        return [
            $this->tagTaxonomy,
        ];
    }
}
