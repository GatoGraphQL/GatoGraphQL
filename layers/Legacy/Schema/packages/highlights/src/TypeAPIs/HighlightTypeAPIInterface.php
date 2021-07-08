<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface HighlightTypeAPIInterface
{
    /**
     * Return the highlight's ID
     */
    public function getID(object $highlight): string | int;
    /**
     * Indicates if the passed object is of type Highlight
     */
    public function isInstanceOfHighlightType(object $object): bool;
    /**
     * Get the highlight with provided ID or, if it doesn't exist, null
     */
    public function getHighlight(int | string $id): ?object;
    /**
     * Indicate if an highlight with provided ID exists
     */
    public function highlightExists(int | string $id): bool;
}
