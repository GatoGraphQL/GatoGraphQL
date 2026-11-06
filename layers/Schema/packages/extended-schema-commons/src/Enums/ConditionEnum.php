<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\Enums;

enum ConditionEnum: string
{
    case IS_NULL = 'IS_NULL';
    case IS_EMPTY = 'IS_EMPTY';
    case ALWAYS = 'ALWAYS';
}
