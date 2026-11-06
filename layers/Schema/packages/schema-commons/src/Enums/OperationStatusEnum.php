<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Enums;

enum OperationStatusEnum: string
{
    case SUCCESS = 'SUCCESS';
    case FAILURE = 'FAILURE';
}
