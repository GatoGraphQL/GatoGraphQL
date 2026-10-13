<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Enums;

/**
 * Kind of URL path stored in a Settings option:
 *
 * - ENDPOINT: the whole path of an endpoint or client
 *   (eg: the single endpoint, under "graphql/")
 * - CUSTOM_POST_TYPE_BASE: the base slug of the permalinks
 *   of a custom post type (eg: the persisted queries, under
 *   "graphql-query/{slug}/")
 */
enum URLPathSettingType: string
{
    case ENDPOINT = 'endpoint';
    case CUSTOM_POST_TYPE_BASE = 'customPostTypeBase';
}
