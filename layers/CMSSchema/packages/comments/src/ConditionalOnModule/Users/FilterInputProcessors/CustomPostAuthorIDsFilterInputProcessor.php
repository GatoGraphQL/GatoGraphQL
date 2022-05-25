<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class CustomPostAuthorIDsFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(): string
    {
        return 'custompost-author-ids';
    }
}
