<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class PasswordFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(): string
    {
        return 'password';
    }
}
