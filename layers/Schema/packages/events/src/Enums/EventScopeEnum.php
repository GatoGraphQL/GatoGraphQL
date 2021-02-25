<?php

declare(strict_types=1);

namespace PoPSchema\Events\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class EventScopeEnum extends AbstractEnum
{
    protected function getEnumName(): string
    {
        return 'EventScope';
    }
    public function getValues(): array
    {
        return [
            \POP_EVENTS_SCOPE_FUTURE,
            \POP_EVENTS_SCOPE_CURRENT,
            \POP_EVENTS_SCOPE_PAST,
        ];
    }
}
