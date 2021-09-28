<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition as UpstreamSchemaDefinition;

class SchemaDefinition extends UpstreamSchemaDefinition
{
    const ARGNAME_PERSISTED_FRAGMENTS = 'persistedFragments';
    const ARGNAME_PERSISTED_QUERIES = 'persistedQueries';
    const ARGNAME_FRAGMENT_RESOLUTION = 'resolution';
}
