<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator;

/**
 * Add IFTTT on @cacheControl(maxAge: 0) on all rules that
 * have been applied some ACL rule
 */
class ValidateACLDirectiveForFieldsPrivateSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator
{
    use NoCacheConfigurableAccessControlRelationalTypeResolverDecoratorTrait;

    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries(): array
    {
        $configurationEntries = [];
        foreach ($this->getAccessControlManager()->getFieldEntries() as $group => $entries) {
            $configurationEntries = array_merge(
                $configurationEntries,
                $entries
            );
        }
        return $configurationEntries;
    }
}
