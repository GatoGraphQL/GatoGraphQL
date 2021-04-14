<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\TypeAPIs;

use PoPSchema\TaxonomyMeta\ComponentConfiguration;
use PoPSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;
use PoPSchema\SchemaCommons\Facades\Services\AllowOrDenySettingsServiceFacade;

abstract class AbstractTaxonomyMetaTypeAPI implements TaxonomyMetaTypeAPIInterface
{
    final public function getTaxonomyMeta(string | int $termID, string $key, bool $single = false): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getTaxonomyMetaEntries();
        $behavior = ComponentConfiguration::getTaxonomyMetaBehavior();
        $allowOrDenySettingsService = AllowOrDenySettingsServiceFacade::getInstance();
        if (!$allowOrDenySettingsService->isEntryAllowed($key, $entries, $behavior)) {
            return null;
        }
        return $this->doGetTaxonomyMeta($termID, $key, $single);
    }

    abstract protected function doGetTaxonomyMeta(string | int $termID, string $key, bool $single = false): mixed;
}
