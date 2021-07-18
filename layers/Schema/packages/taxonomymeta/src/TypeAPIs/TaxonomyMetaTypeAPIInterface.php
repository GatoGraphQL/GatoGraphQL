<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\TypeAPIs;

interface TaxonomyMetaTypeAPIInterface
{
    public function getTaxonomyTermMeta(string | int $termID, string $key, bool $single = false): mixed;
}
