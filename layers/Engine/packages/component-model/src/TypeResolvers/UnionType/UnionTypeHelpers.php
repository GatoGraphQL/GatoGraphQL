<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\UnionType;

use PoP\Root\App;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

use function explode;

class UnionTypeHelpers
{
    /**
     * Extracts the DB key and ID from the object ID
     */
    public static function extractDBObjectTypeAndID(string $composedDBKeyObjectID): array
    {
        $parts = explode(
            UnionTypeSymbols::OBJECT_COMPOSED_TYPE_ID_SEPARATOR,
            $composedDBKeyObjectID
        );
        // If the object could not be loaded, $composedDBKeyObjectID will be all ID, with no $dbKey
        if (count($parts) === 1) {
            return ['', $parts[0]];
        }
        return $parts;
    }

    /**
     * Extracts the ID from the object ID
     */
    public static function extractDBObjectID(string $composedDBObjectTypeAndID): string | int
    {
        $elements = explode(
            UnionTypeSymbols::OBJECT_COMPOSED_TYPE_ID_SEPARATOR,
            $composedDBObjectTypeAndID
        );
        // If the UnionTypeResolver didn't have a TypeResolver to process the passed object, the Type will not be added
        // In that case, the ID will be on the first position
        return count($elements) == 1 ? $elements[0] : $elements[1];
    }

    /**
     * Creates a composed string containing the type and ID of the dbObject
     */
    public static function getObjectComposedTypeAndID(RelationalTypeResolverInterface $relationalTypeResolver, int | string $id): string
    {
        return
            $relationalTypeResolver->getTypeOutputDBKey() .
            UnionTypeSymbols::OBJECT_COMPOSED_TYPE_ID_SEPARATOR .
            (string) $id;
    }

    /**
     * Return a class or another depending on these possibilities:
     *
     * - If there is more than 1 target type resolver for the Union, return the Union
     * - (By configuration) If there is only one target, return that one directly
     *   and not the Union (since it's more efficient)
     * - If there are none types, return `null`. As a consequence,
     *   the ID is returned as a field, not as a connection
     */
    public static function getUnionOrTargetObjectTypeResolver(UnionTypeResolverInterface $unionTypeResolver): ?UnionTypeResolverInterface
    {
        $targetTypeResolvers = $unionTypeResolver->getTargetObjectTypeResolvers();
        if ($targetTypeResolvers) {
            // By configuration: If there is only 1 item, return only that one
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->useSingleTypeInsteadOfUnionType()) {
                return count($targetTypeResolvers) == 1 ?
                    $targetTypeResolvers[0] :
                    $unionTypeResolver;
            }
            return $unionTypeResolver;
        }
        return null;
    }
}
