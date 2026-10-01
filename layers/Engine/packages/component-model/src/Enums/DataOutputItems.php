<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

enum DataOutputItems: string
{
    case META = 'meta';
    case DATASET_COMPONENT_SETTINGS = 'datasetcomponentsettings';
    case COMPONENT_DATA = 'componentdata';
    case DATABASES = 'databases';
    case SESSION = 'session';
}
