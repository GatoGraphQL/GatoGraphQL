<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\UnionType;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

use function explode;

class UnionTypeHelpers
{
    /**
     * Extracts the DB key and ID from the object ID
     *
     * @return array{0:string,1:string|int}
     */
    public static function extractObjectTypeAndID(string|int $maybeComposedTypeOutputKeyObjectID): array
    {
        if (is_int($maybeComposedTypeOutputKeyObjectID)) {
            return $maybeComposedTypeOutputKeyObjectID;
        }
        /** @var string */
        $composedTypeOutputKeyObjectID = $maybeComposedTypeOutputKeyObjectID;
        $parts = explode(
            UnionTypeSymbols::OBJECT_COMPOSED_TYPE_ID_SEPARATOR,
            $composedTypeOutputKeyObjectID
        );
        // If the object could not be loaded, $composedTypeOutputKeyObjectID will be all ID, with no $typeOutputKey
        if (count($parts) === 1) {
            return ['', $parts[0]];
        }
        /** @var array{0:string,1:string|int} */
        return $parts;
    }

    /**
     * Extracts the ID from the object ID
     */
    public static function extractDBObjectID(string|int $maybeComposedDBObjectTypeAndID): string|int
    {
        if (is_int($maybeComposedDBObjectTypeAndID)) {
            return $maybeComposedDBObjectTypeAndID;
        }
        /** @var string */
        $composedDBObjectTypeAndID = $maybeComposedDBObjectTypeAndID;
        $elements = explode(
            UnionTypeSymbols::OBJECT_COMPOSED_TYPE_ID_SEPARATOR,
            $composedDBObjectTypeAndID
        );
        // If the UnionTypeResolver didn't have a TypeResolver to process the passed object, the Type will not be added
        // In that case, the ID will be on the first position
        return count($elements) === 1 ? $elements[0] : $elements[1];
    }

    /**
     * Creates a composed string containing the type and ID of the resolvedObject
     */
    public static function getObjectComposedTypeAndID(RelationalTypeResolverInterface $relationalTypeResolver, int|string $id): string
    {
        return
            $relationalTypeResolver->getTypeOutputKey() .
            UnionTypeSymbols::OBJECT_COMPOSED_TYPE_ID_SEPARATOR .
            (string) $id;
    }
}
