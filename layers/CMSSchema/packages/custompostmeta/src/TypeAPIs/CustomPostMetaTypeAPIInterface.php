<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMeta\TypeAPIs;

use InvalidArgumentException;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

interface CustomPostMetaTypeAPIInterface extends MetaTypeAPIInterface
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
    public function getCustomPostMeta(string | int $customPostID, string $key, bool $single = false, array $options = []): mixed;
}
