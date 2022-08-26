<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Constants;

enum Order: string
{
    case Asc = 'ASC';
    case Desc = 'DESC';
}
