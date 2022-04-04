<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition as UpstreamSchemaDefinition;

class SchemaDefinition extends UpstreamSchemaDefinition
{
    public final const TYPE_KIND = 'typeKind';
    public final const TYPE_NAME = 'typeName';

    public final const PERSISTED_FRAGMENTS = 'persistedFragments';
    public final const PERSISTED_QUERIES = 'persistedQueries';
    public final const FRAGMENT_RESOLUTION = 'resolution';
}
