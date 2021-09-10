<?php

declare(strict_types=1);

namespace PoP\Multisite\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;

trait SiteFieldResolverTrait
{
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $site = $resultItem;
        // Only for the current site. For other sites must be implemented through a "multisite" package
        // The parent class will return the correct value. That's why if it is not the current site, then already return the expected error
        if ($site->getHost() != $cmsengineapi->getHost()) {
            return false;
        }
        return parent::resolveCanProcessResultItem($objectTypeResolver, $resultItem, $fieldName, $fieldArgs);
    }
}
