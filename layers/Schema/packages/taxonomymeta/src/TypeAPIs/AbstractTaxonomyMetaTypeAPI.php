<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\TypeAPIs;

use InvalidArgumentException;
use PoPSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
use PoPSchema\TaxonomyMeta\ComponentConfiguration;

abstract class AbstractTaxonomyMetaTypeAPI extends AbstractMetaTypeAPI implements TaxonomyMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, throw an exception
     *
     * @throws InvalidArgumentException
     */
    final public function getTaxonomyTermMeta(string | int $termID, string $key, bool $single = false): mixed
    {
        $entries = ComponentConfiguration::getTaxonomyMetaEntries();
        $behavior = ComponentConfiguration::getTaxonomyMetaBehavior();
        $this->assertIsEntryAllowed($entries, $behavior, $key);
        return $this->doGetTaxonomyMeta($termID, $key, $single);
    }

    abstract protected function doGetTaxonomyMeta(string | int $termID, string $key, bool $single = false): mixed;
}
