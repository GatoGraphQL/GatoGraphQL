<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition as UpstreamSchemaDefinition;

class SchemaDefinition extends UpstreamSchemaDefinition
{
    const PERSISTED_FRAGMENTS = 'persistedFragments';
    const PERSISTED_QUERIES = 'persistedQueries';
    const FRAGMENT_RESOLUTION = 'resolution';
}
