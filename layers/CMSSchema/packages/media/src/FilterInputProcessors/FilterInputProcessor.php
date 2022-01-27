<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public const FILTERINPUT_MIME_TYPES = 'filterinput-mime-types';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_MIME_TYPES],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_MIME_TYPES:
                $query['mime-types'] = $value;
                break;
        }
    }
}
