<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\Constants;

class HookNames
{
    /**
     * Filter the content stored for a custom post, before anything reads it.
     *
     * A page builder that renders its own layout may keep the stored content
     * as a derived cache of that layout, in which case the custom post has no
     * content of its own to report.
     */
    public const CUSTOMPOST_RAW_CONTENT = __CLASS__ . ':customPost:rawContent';
}
