<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class CommentStatusFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'status';
    }
}
