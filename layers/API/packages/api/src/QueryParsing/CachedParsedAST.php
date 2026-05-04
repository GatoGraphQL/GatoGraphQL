<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryParsing;

use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

/**
 * Serializable bundle stored in the persistent cache. Both members must be
 * serialized together so that PHP's `serialize()` preserves intra-graph object
 * identity — i.e. the FieldInterface instances inside `$objectResolvedFieldValueReferencedFields`
 * remain the same objects as the corresponding nodes inside `$document` after
 * unserialize. Cache the two separately and identity is lost.
 *
 * @internal
 */
final class CachedParsedAST
{
    /**
     * @param FieldInterface[] $objectResolvedFieldValueReferencedFields
     */
    public function __construct(
        public readonly Document $document,
        public readonly array $objectResolvedFieldValueReferencedFields,
    ) {
    }
}
