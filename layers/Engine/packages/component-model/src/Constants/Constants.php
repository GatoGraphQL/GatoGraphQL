<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Constants;

class Constants
{
    /**
     * Token used to separate the type from the field for setting version constraints
     */
    public final const TYPE_FIELD_SEPARATOR = '.';

    /**
     * Create the path to identify under what Type is the data for some relationalfield
     */
    public final const RELATIONAL_FIELD_PATH_SEPARATOR = '.';
}
