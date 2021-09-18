<?php

declare(strict_types=1);

namespace PoPSchema\StancesWP\ConditionalOnContext\AddStanceTypeToCustomPostUnionTypes\Overrides\SchemaServices\ObjectTypeResolverPickers;

use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\ObjectTypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Stances\ConditionalOnContext\AddStanceTypeToCustomPostUnionTypes\SchemaServices\ObjectTypeResolverPickers\StanceCustomPostObjectTypeResolverPicker as UpstreamStanceCustomPostObjectTypeResolverPicker;

class StanceCustomPostObjectTypeResolverPicker extends UpstreamStanceCustomPostObjectTypeResolverPicker implements CustomPostObjectTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_USERSTANCE_POSTTYPE_USERSTANCE;
    }
}
