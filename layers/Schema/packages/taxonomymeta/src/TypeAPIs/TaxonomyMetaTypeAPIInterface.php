<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\TypeAPIs;

use InvalidArgumentException;

interface TaxonomyMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, throw an exception
     *
     * @throws InvalidArgumentException
     */
    public function getTaxonomyTermMeta(string | int $termID, string $key, bool $single = false): mixed;
}
