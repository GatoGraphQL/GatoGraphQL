<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Services\AbstractBasicService;
use WeakMap;

use function array_key_exists;
use function spl_object_id;

class DataloadHelperService extends AbstractBasicService implements DataloadHelperServiceInterface
{
    /**
     * Memoizes results of `getTypeResolverFromSubcomponentField` per
     * (field, resolver) instance pair. The method is called inside nested
     * loops in `AbstractComponentProcessor::initModelProps()` (once per
     * relational / conditional field per component per operation), and
     * the result depends only on that pair, so caching avoids re-walking
     * the type graph on every call. Uses `array_key_exists` (not `isset`)
     * so that a legitimately cached `null` result is not treated as a
     * cache miss.
     *
     * Weakly keyed by the field, so the entries go away with the AST of
     * the executed document. Keyed by `spl_object_id`, they piled up for
     * every document executed in the request, and an ID reused by a field
     * created after the previous one was freed got the other field's
     * result back.
     *
     * @var WeakMap<FieldInterface,array<int,RelationalTypeResolverInterface|null>>|null
     */
    private ?WeakMap $typeResolverFromSubcomponentFieldCache = null;

    private ?ComponentProcessorManagerInterface $componentProcessorManager = null;

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
        if ($this->typeResolverFromSubcomponentFieldCache === null) {
            /** @var WeakMap<FieldInterface,array<int,RelationalTypeResolverInterface|null>> */
            $typeResolverFromSubcomponentFieldCache = new WeakMap();
            $this->typeResolverFromSubcomponentFieldCache = $typeResolverFromSubcomponentFieldCache;
        }
        $fieldCache = $this->typeResolverFromSubcomponentFieldCache[$field] ?? [];
        $relationalTypeResolverObjectID = spl_object_id($relationalTypeResolver);
        if (array_key_exists($relationalTypeResolverObjectID, $fieldCache)) {
            return $fieldCache[$relationalTypeResolverObjectID];
        }
        $fieldCache[$relationalTypeResolverObjectID] = $this->doGetTypeResolverFromSubcomponentField($relationalTypeResolver, $field);
        $this->typeResolverFromSubcomponentFieldCache[$field] = $fieldCache;
        return $fieldCache[$relationalTypeResolverObjectID];
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
