<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta\TypeAPIs;

use InvalidArgumentException;

interface CustomPostMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, throw an exception
     *
     * @throws InvalidArgumentException
     */
    public function getCustomPostMeta(string | int $customPostID, string $key, bool $single = false): mixed;
}
