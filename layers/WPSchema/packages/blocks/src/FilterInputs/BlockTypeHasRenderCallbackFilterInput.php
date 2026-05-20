<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;

class BlockTypeHasRenderCallbackFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey(): string
    {
        return 'hasRenderCallback';
    }
}
