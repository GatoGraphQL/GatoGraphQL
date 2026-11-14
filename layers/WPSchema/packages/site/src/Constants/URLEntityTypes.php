<?php

declare(strict_types=1);

namespace PoPWPSchema\Site\Constants;

/**
 * The kinds of entity that `_urlToEntity` resolves a URL to, as the `type`
 * property of its result.
 */
class URLEntityTypes
{
    public const CUSTOM_POST = 'customPost';
    public const MEDIA = 'media';
    public const TAXONOMY_TERM = 'taxonomyTerm';
    public const USER = 'user';
    public const HOMEPAGE = 'homepage';
    public const CUSTOM_POST_TYPE_ARCHIVE = 'customPostTypeArchive';
}
