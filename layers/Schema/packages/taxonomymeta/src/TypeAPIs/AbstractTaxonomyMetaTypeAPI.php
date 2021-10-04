<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\TypeAPIs;

use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;
use PoPSchema\TaxonomyMeta\ComponentConfiguration;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractTaxonomyMetaTypeAPI implements TaxonomyMetaTypeAPIInterface
{
    protected AllowOrDenySettingsServiceInterface $allowOrDenySettingsService;

    #[Required]
    final public function autowireAbstractTaxonomyMetaTypeAPI(AllowOrDenySettingsServiceInterface $allowOrDenySettingsService): void
    {
        $this->allowOrDenySettingsService = $allowOrDenySettingsService;
    }

    final public function getTaxonomyTermMeta(string | int $termID, string $key, bool $single = false): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getTaxonomyMetaEntries();
        $behavior = ComponentConfiguration::getTaxonomyMetaBehavior();
        if (!$this->allowOrDenySettingsService->isEntryAllowed($key, $entries, $behavior)) {
            return null;
        }
        return $this->doGetTaxonomyMeta($termID, $key, $single);
    }

    abstract protected function doGetTaxonomyMeta(string | int $termID, string $key, bool $single = false): mixed;
}
