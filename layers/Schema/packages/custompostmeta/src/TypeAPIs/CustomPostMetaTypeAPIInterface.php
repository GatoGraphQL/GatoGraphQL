<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta\TypeAPIs;

interface CustomPostMetaTypeAPIInterface
{
    public function getCustomPostMeta(string | int $customPostID, string $key, bool $single = false): mixed;
}
