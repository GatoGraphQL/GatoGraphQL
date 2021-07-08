<?php

declare(strict_types=1);

namespace PoPSchema\StancesWP\ConditionalOnContext\AddStanceTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;
use PoPSchema\Stances\ConditionalOnContext\AddStanceTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers\StanceCustomPostTypeResolverPicker as UpstreamStanceCustomPostTypeResolverPicker;

class StanceCustomPostTypeResolverPicker extends UpstreamStanceCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_USERSTANCE_POSTTYPE_USERSTANCE;
    }
}
