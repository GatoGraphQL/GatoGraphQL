<?php

declare(strict_types=1);

namespace PoPSchema\StancesWP\ConditionalOnContext\AddStanceTypeToCustomPostUnionTypes\Overrides\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Stances\ConditionalOnContext\AddStanceTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers\StanceCustomPostObjectTypeResolverPicker as UpstreamStanceCustomPostObjectTypeResolverPicker;

class StanceCustomPostObjectTypeResolverPicker extends UpstreamStanceCustomPostObjectTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_USERSTANCE_POSTTYPE_USERSTANCE;
    }
}
