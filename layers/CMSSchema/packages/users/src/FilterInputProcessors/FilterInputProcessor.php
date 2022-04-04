<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;

class FilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const FILTERINPUT_NAME = 'filterinput-name';
    public final const FILTERINPUT_USERNAME_OR_USERNAMES = 'filterinput-username-or-usernames';
    public final const FILTERINPUT_EMAIL_OR_EMAILS = 'filterinput-email-or-emails';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::FILTERINPUT_NAME],
            [self::class, self::FILTERINPUT_USERNAME_OR_USERNAMES],
            [self::class, self::FILTERINPUT_EMAIL_OR_EMAILS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::FILTERINPUT_NAME:
                $query['name'] = $value;
                break;
            case self::FILTERINPUT_USERNAME_OR_USERNAMES:
                $query['username'] = is_array($value) ? $value : [$value];
                break;
            case self::FILTERINPUT_EMAIL_OR_EMAILS:
                $query['emails'] = is_array($value) ? $value : [$value];
                break;
        }
    }
}
