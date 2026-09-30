<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Enums;

enum ParamValues: string
{
    case ENABLED = 'enabled';
    case DISABLED = 'disabled';
}
