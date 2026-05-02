<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Services\AbstractBasicService;

class DataloadHelperService extends AbstractBasicService implements DataloadHelperServiceInterface
{
    private ?ComponentProcessorManagerInterface $componentProcessorManager = null;

    /**
     * Memoizes results of `getTypeResolverFromSubcomponentField` keyed by
     * spl_object_id pair. The method is called inside nested loops in
     * `AbstractComponentProcessor::initModelProps()` (once per relational /
     * conditional field per component per operation), and the result depends
     * only on the (resolver, field) instance pair — both of which are stable
     * for the lifetime of a request — so caching avoids re-walking the type
     * graph on every call. Uses `array_key_exists` (not `isset`) so that a
     * legitimately cached `null` result is not treated as a cache miss.
     *
     * @var array<string,RelationalTypeResolverInterface|null>
     */
    private array $typeResolverFromSubcomponentFieldCache = [];

    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        if ($this->componentProcessorManager === null) {
            /** @var ComponentProcessorManagerInterface */
            $componentProcessorManager = $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
            $this->componentProcessorManager = $componentProcessorManager;
        }
        return $this->componentProcessorManager;
    }

    /**
     * Accept RelationalTypeResolverInterface as param, instead of the more natural
     * ObjectTypeResolverInterface, to make it easy within the application to check
     * for this result without checking in advance what's the typeResolver.
     */
    public function getTypeResolverFromSubcomponentField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
    ): ?RelationalTypeResolverInterface {
        $cacheKey = spl_object_id($relationalTypeResolver) . '.' . spl_object_id($field);
        if (array_key_exists($cacheKey, $this->typeResolverFromSubcomponentFieldCache)) {
            return $this->typeResolverFromSubcomponentFieldCache[$cacheKey];
        }
        return $this->typeResolverFromSubcomponentFieldCache[$cacheKey] = $this->doGetTypeResolverFromSubcomponentField($relationalTypeResolver, $field);
    }

    private function doGetTypeResolverFromSubcomponentField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
    ): ?RelationalTypeResolverInterface {
        /**
         * Because the UnionTypeResolver doesn't know yet which TypeResolver will be used
         * (that depends on each object), it can't resolve this functionality
         */
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            return null;
        }
        // By now, the typeResolver must be ObjectType
        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;

        // Check if this field doesn't have a typeResolver
        $subcomponentFieldNodeTypeResolver = $objectTypeResolver->getFieldTypeResolver($field);
        if (
            $subcomponentFieldNodeTypeResolver === null
            || !($subcomponentFieldNodeTypeResolver instanceof RelationalTypeResolverInterface)
        ) {
            return null;
        }
        return $subcomponentFieldNodeTypeResolver;
    }
}
