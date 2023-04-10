<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Constants;

class HookNames
{
    public final const HOOK_QUERYABLE_CUSTOMPOST_TYPES = __CLASS__ . ':queryable-custompost-types';
    public final const HOOK_REJECTED_QUERYABLE_CUSTOMPOST_TYPES = __CLASS__ . ':rejected-queryable-custompost-types';
    public final const HOOK_QUERYABLE_TAG_TAXONOMIES = __CLASS__ . ':queryable-tag-taxonomies';
    public final const HOOK_REJECTED_QUERYABLE_TAG_TAXONOMIES = __CLASS__ . ':rejected-queryable-tag-taxonomies';
    public final const HOOK_QUERYABLE_CATEGORY_TAXONOMIES = __CLASS__ . ':queryable-category-taxonomies';
    public final const HOOK_REJECTED_QUERYABLE_CATEGORY_TAXONOMIES = __CLASS__ . ':rejected-queryable-category-taxonomies';

    public final const ADMIN_ENDPOINT_GROUP_MODULE_CONFIGURATION = __CLASS__ . ':admin-endpoint-group-module-configuration';
    public final const ADMIN_ENDPOINT_GROUP_MODULE_CLASSES_TO_SKIP = __CLASS__ . ':admin-endpoint-group-module-classes-to-skip';
}
