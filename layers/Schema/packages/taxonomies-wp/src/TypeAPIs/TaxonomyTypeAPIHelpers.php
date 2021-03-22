<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomiesWP\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TaxonomyTypeAPIHelpers
{
    public static function getTermObjectAndID(mixed $termObjectOrID): array
    {
        if (is_object($termObjectOrID)) {
            $termObject = $termObjectOrID;
            $termObjectID = $termObject->ID;
        } else {
            $termObjectID = $termObjectOrID;
            $termObject = \get_term($termObjectID);
        }
        return [
            $termObject,
            $termObjectID,
        ];
    }
}
