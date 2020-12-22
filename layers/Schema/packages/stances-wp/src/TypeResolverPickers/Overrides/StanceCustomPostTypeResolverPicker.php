<?php

declare(strict_types=1);

namespace PoPSchema\StancesWP\TypeResolverPickers\Overrides;

use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\CustomPostsWP\TypeResolverPickers\NoCastCustomPostTypeResolverPickerTrait;

class StanceCustomPostTypeResolverPicker extends \PoPSchema\Stances\TypeResolverPickers\Optional\StanceCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    use NoCastCustomPostTypeResolverPickerTrait;

    public function getCustomPostType(): string
    {
        return \POP_USERSTANCE_POSTTYPE_USERSTANCE;
    }
}
