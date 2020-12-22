<?php

declare(strict_types=1);

namespace PoP\API\Schema;

class SchemaDefinition extends \PoP\ComponentModel\Schema\SchemaDefinition
{
    const ARGNAME_PERSISTED_FRAGMENTS = 'persistedFragments';
    const ARGNAME_PERSISTED_QUERIES = 'persistedQueries';
    const ARGNAME_FRAGMENT_RESOLUTION = 'resolution';
}
