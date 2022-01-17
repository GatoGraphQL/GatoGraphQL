<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMeta\TypeAPIs;

use InvalidArgumentException;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

interface TaxonomyMetaTypeAPIInterface extends MetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, and passing option "assert-is-meta-key-allowed",
     * then throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param array<string,mixed> $options
     * @throws InvalidArgumentException
     */
    public function getTaxonomyTermMeta(string | int $termID, string $key, bool $single = false, array $options = []): mixed;
}
