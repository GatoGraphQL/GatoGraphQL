<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Constants;

class HookNames
{
    public final const QUERYABLE_CUSTOMPOST_TYPES = __CLASS__ . ':queryable-custompost-types';
    public final const REJECTED_QUERYABLE_CUSTOMPOST_TYPES = __CLASS__ . ':rejected-queryable-custompost-types';
    public final const QUERYABLE_TAG_TAXONOMIES = __CLASS__ . ':queryable-tag-taxonomies';
    public final const REJECTED_QUERYABLE_TAG_TAXONOMIES = __CLASS__ . ':rejected-queryable-tag-taxonomies';
    public final const QUERYABLE_CATEGORY_TAXONOMIES = __CLASS__ . ':queryable-category-taxonomies';
    public final const REJECTED_QUERYABLE_CATEGORY_TAXONOMIES = __CLASS__ . ':rejected-queryable-category-taxonomies';

    public final const FORBID_ACCESS = __CLASS__ . ':forbid-access';
}
