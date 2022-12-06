<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FilterInputs;

use PoPCMSSchema\CustomPosts\FilterInputs\CustomPostStatusFilterInput as UpstreamCustomPostStatusFilterInput;

class CustomPostStatusFilterInput extends UpstreamCustomPostStatusFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'custompost-status';
    }
}
