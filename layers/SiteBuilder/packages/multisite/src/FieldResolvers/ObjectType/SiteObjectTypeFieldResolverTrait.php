<?php

declare(strict_types=1);

namespace PoP\Multisite\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\FunctionAPIFactory;
use PoP\Multisite\ObjectModels\Site;

trait SiteObjectTypeFieldResolverTrait
{
    /**
     * @todo This function has been removed, adapt it to whatever needs be done!
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): bool {
        $cmsengineapi = FunctionAPIFactory::getInstance();
        /** @var Site */
        $site = $object;
        // Only for the current site. For other sites must be implemented through a "multisite" package
        // The parent class will return the correct value. That's why if it is not the current site, then already return the expected error
        if ($site->getHost() != $cmsengineapi->getHost()) {
            return false;
        }
        return parent::resolveCanProcessObject($objectTypeResolver, $fieldDataAccessor, $object);
    }
}
