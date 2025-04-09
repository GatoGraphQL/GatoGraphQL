<?php

declare(strict_types=1);

namespace PoPCMSSchema\Meta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use stdClass;

trait EntityObjectTypeFieldResolverTrait
{
    /**
     * @param string[] $metaKeys
     */
    public function resolveMetaKeysValue(
        array $metaKeys,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): mixed {
        /** @var stdClass|null */
        $filter = $fieldDataAccessor->getValue('filter');
        if ($filter === null) {
            return $metaKeys;
        }
        if (isset($filter->include)) {
            /** @var string[] */
            $include = $filter->include;
            $metaKeys = array_values(array_intersect($metaKeys, $include));
        }
        if (isset($filter->exclude)) {
            /** @var string[] */
            $exclude = $filter->exclude;
            $metaKeys = array_values(array_diff($metaKeys, $exclude));
        }
        return $metaKeys;
    }
}
