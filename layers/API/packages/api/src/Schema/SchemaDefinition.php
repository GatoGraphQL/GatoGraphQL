<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition as UpstreamSchemaDefinition;

class SchemaDefinition extends UpstreamSchemaDefinition
{
    const TYPE_TYPE = 'typeType';
    const IMPLEMENTED_INTERFACES = 'implementedInterfaces';

    const TYPE_OBJECT = 'object';
    const TYPE_INTERFACE = 'interface';
    const TYPE_UNION = 'union';
    const TYPE_SCALAR = 'scalar';
    const TYPE_ENUM = 'enum';
    const TYPE_INPUT_OBJECT = 'inputObject';
    
    const PERSISTED_FRAGMENTS = 'persistedFragments';
    const PERSISTED_QUERIES = 'persistedQueries';
    const FRAGMENT_RESOLUTION = 'resolution';
}
