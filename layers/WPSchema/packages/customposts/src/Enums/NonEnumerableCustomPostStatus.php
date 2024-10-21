<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Enums;

class NonEnumerableCustomPostStatus
{
    /**
     * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
     *       Until then, this value can't be added to `FilterCustomPostStatusEnum`,
     *       but it's still referenced by `CustomPostObjectTypeDataLoader` to be able
     *       to fetch custom posts with this status
     */
    public final const AUTO_DRAFT = 'auto-draft';
}
