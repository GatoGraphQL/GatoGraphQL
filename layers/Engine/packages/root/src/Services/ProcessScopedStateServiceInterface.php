<?php

declare(strict_types=1);

namespace PoP\Root\Services;

/**
 * A service holding state which is written to and read back over the course
 * of the process, rather than derived afresh on every call.
 *
 * Most services hold nothing, or hold only a memoized derivation: asking
 * twice gives the same answer, and when the answer was computed does not
 * matter. A service marked here is different. Asking twice may give
 * different answers, and the answer depends on what has run so far, so
 * whoever reads it is reading a moment rather than a fact.
 *
 * The state lasts as long as the process does, which in PHP means one web
 * request or one CLI command, and it deliberately outlives the container the
 * service was resolved from: a query may be executed by a container of its
 * own, and what was counted or decided on one side has to be legible on the
 * other. Clearing it when a container is built, a request is dispatched or a
 * query begins would therefore throw away the very thing it is kept for.
 *
 * The marker carries no methods. What clearing means, and whether it is
 * possible at all, belongs to each implementation: some hold a tally which
 * only makes sense read once at the end, others a decision taken up front.
 * An implementation offering a way to clear its state says on that method who
 * may call it.
 */
interface ProcessScopedStateServiceInterface
{
}
