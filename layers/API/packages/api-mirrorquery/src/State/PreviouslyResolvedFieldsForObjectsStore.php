<?php

declare(strict_types=1);

namespace PoPAPI\APIMirrorQuery\State;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

/**
 * Holds the per-(typeOutputKey, objectID) list of fields already
 * resolved during the response-formatting pass.
 *
 * Previously this lived on `App::getState('previously-resolved-fields-for-objects')`
 * as a deeply-nested array keyed by `[typeOutputKey][objectID][]`. Per
 * iteration in `MirrorQueryDataStructureFormatter::addObjectData`,
 * the formatter would `App::getState(...)` (COW-shared with state),
 * mutate the local copy (triggers PHP COW deep-copy of the structure),
 * and `override(...)` it back. With ~8K calls × ~5 fields each = 40K
 * read-modify-write cycles per request and the state growing
 * monotonically, this dominated request memory at ~1.3GB on the
 * AI-translation profile.
 *
 * Wrapping the data in an object eliminates the COW: PHP passes
 * objects by handle, so mutating `$store->add(...)` modifies the
 * shared instance without any deep-copy. The store itself is parked
 * once on App state (per-request lifecycle), and consumers call
 * `add()`/`getForObject()` directly.
 */
final class PreviouslyResolvedFieldsForObjectsStore
{
    /** @var array<string,array<string|int,FieldInterface[]>> */
    private array $data = [];

    public function add(string $typeOutputKey, string|int $objectID, FieldInterface $field): void
    {
        $this->data[$typeOutputKey][$objectID][] = $field;
    }

    /**
     * @return FieldInterface[]
     */
    public function getForObject(string $typeOutputKey, string|int $objectID): array
    {
        return $this->data[$typeOutputKey][$objectID] ?? [];
    }
}
