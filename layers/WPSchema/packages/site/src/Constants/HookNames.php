<?php

declare(strict_types=1);

namespace PoPWPSchema\Site\Constants;

class HookNames
{
    /**
     * The entity a URL points to, as `_urlToEntity` resolves it (or `null`
     * when it resolves none), for another package to adjust: a translation
     * plugin answers with the entity in the language the URL names, since
     * WordPress resolves a slug alone.
     */
    public const URL_TO_ENTITY = __CLASS__ . ':url-to-entity';

    /**
     * The URL that `_urlToEntity` matches against the rewrite rules and
     * compares with the URL of the entity it finds. A translation plugin
     * removes the language from it (a prefix, a subdomain or a domain), so
     * that WordPress resolves it and both URLs compare equal whatever
     * language each one is in.
     */
    public const URL_TO_ENTITY_COMPARABLE_URL = __CLASS__ . ':url-to-entity-comparable-url';
}
