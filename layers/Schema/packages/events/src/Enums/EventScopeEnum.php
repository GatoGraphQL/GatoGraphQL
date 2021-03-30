<?php

declare(strict_types=1);

namespace PoPSchema\Events\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;
use PoPSchema\Events\Constants\Scopes;

class EventScopeEnum extends AbstractEnum
{
    protected function getEnumName(): string
    {
        return 'EventScope';
    }
    public function getValues(): array
    {
        return [
            Scopes::FUTURE,
            Scopes::CURRENT,
            Scopes::PAST,
        ];
    }
}
