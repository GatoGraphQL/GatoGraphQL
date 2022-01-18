<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition as UpstreamSchemaDefinition;

class SchemaDefinition extends UpstreamSchemaDefinition
{
    public const TYPE_KIND = 'typeKind';
    public const TYPE_NAME = 'typeName';

    public const PERSISTED_FRAGMENTS = 'persistedFragments';
    public const PERSISTED_QUERIES = 'persistedQueries';
    public const FRAGMENT_RESOLUTION = 'resolution';
}
