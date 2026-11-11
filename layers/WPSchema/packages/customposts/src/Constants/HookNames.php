<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Constants;

class HookNames
{
    /**
     * The ID of the custom post a URL points to, as `url_to_postid()`
     * resolves it, for a translation plugin to correct: a URL in a
     * language must point to the post in that language, and
     * `url_to_postid()` resolves the slug alone.
     */
    public const URL_TO_CUSTOM_POST_ID = __CLASS__ . ':url-to-custom-post-id';
}
