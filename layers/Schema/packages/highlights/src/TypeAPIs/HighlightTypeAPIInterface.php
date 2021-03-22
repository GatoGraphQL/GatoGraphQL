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
     *
     * @param [type] $highlight
     * @return void
     */
    public function getID($highlight);
    /**
     * Indicates if the passed object is of type Highlight
     *
     * @param [type] $object
     */
    public function isInstanceOfHighlightType($object): bool;
    /**
     * Get the highlight with provided ID or, if it doesn't exist, null
     *
     * @param [type] $id
     * @return void
     */
    public function getHighlight($id);
    /**
     * Indicate if an highlight with provided ID exists
     *
     * @param [type] $id
     * @return void
     */
    public function highlightExists($id): bool;
}
