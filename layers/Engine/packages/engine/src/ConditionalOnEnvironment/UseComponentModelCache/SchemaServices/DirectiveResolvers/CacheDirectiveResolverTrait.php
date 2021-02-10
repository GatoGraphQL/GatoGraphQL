<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\DirectiveResolvers;

use PoP\API\Cache\CacheUtils;
use PoP\FieldQuery\FieldQueryInterpreter;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

/**
 * Common functionality between LoadCache and SaveCache directive resolver classes
 */
trait CacheDirectiveResolverTrait
{
    /**
     * This is a "Schema" type directive
     *
     * @return string
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SCHEMA;
    }

    /**
     * Create a unique ID under which to store the cache, based on the type, ID and field (without the alias)
     *
     * @param TypeResolverInterface $typeResolver
     * @param [type] $id
     * @param string $field
     * @return string
     */
    protected function getCacheID(TypeResolverInterface $typeResolver, $id, string $field): string
    {
        // Remove the alias from the field
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        if ($fieldAliasPositionSpan = $fieldQueryInterpreter->getFieldAliasPositionSpanInField($field)) {
            $aliasPos = $fieldAliasPositionSpan[FieldQueryInterpreter::ALIAS_POSITION_KEY];
            $aliasLength = $fieldAliasPositionSpan[FieldQueryInterpreter::ALIAS_LENGTH_KEY];
            $noAliasField = substr($field, 0, $aliasPos) . substr($field, $aliasPos + $aliasLength);
        } else {
            $noAliasField = $field;
        }
        $components = array_merge(
            // The namespacing and version constraints also affect the result of the field, so incorporate them too
            CacheUtils::getSchemaCacheKeyComponents(),
            [
                'name' => $typeResolver->getNamespacedTypeName(),
                'id' => $id,
                'field' => $noAliasField,
            ]
        );
        $cacheID = implode('|', $components);
        /**
         * Hash this key, because the $field may contain reserved characters, such as "()" for the field args:
         * PHP Fatal error:  Uncaught Symfony\Component\Cache\Exception\InvalidArgumentException: Cache key "cache-directive.Root|root|echo(hola)<cache>" contains reserved characters "{}()/\@:". in .../vendor/symfony/cache/CacheItem.php:177
         */
        return hash('md5', $cacheID);
    }
}
