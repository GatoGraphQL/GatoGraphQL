<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class FilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    public final const FILTERINPUT_IS_STICKY = 'filterinput-is-sticky';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_IS_STICKY],
        );
    }

    protected function getQueryArgKey(array $filterInput): string
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_IS_STICKY:
                $query['is-sticky'] = $value;
                break;
        }
    }
}
