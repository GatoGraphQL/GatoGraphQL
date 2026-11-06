<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Enums;

enum Behaviors: string
{
    case ALLOW = 'allow';
    case DENY = 'deny';
}
