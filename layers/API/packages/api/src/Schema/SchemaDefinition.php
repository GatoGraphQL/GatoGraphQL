<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition as UpstreamSchemaDefinition;

class SchemaDefinition extends UpstreamSchemaDefinition
{
    const TYPE_TYPE = 'typeType';
    const IMPLEMENTED_INTERFACES = 'implementedInterfaces';

    const TYPE_OBJECT = 'Object';
    const TYPE_INTERFACE = 'Interface';
    const TYPE_UNION = 'Union';
    const TYPE_SCALAR = 'Scalar';
    const TYPE_ENUM = 'Enum';
    const TYPE_INPUT_OBJECT = 'InputObject';

    const PERSISTED_FRAGMENTS = 'persistedFragments';
    const PERSISTED_QUERIES = 'persistedQueries';
    const FRAGMENT_RESOLUTION = 'resolution';
}
