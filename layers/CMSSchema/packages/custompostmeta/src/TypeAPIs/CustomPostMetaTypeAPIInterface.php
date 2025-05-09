<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMeta\TypeAPIs;

use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
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
     * @throws MetaKeyNotAllowedException
     */
    public function getCustomPostMeta(string|int|object $customPostObjectOrID, string $key, bool $single = false, array $options = []): mixed;

    /**
     * @return array<string,mixed>
     */
    public function getAllCustomPostMeta(string|int|object $customPostObjectOrID): array;

    /**
     * @return string[]
     */
    public function getCustomPostMetaKeys(string|int|object $customPostObjectOrID): array;
}
