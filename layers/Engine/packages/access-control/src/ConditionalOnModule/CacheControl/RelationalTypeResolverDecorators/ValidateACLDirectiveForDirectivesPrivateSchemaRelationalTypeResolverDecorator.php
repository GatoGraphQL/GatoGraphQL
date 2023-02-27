<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPrivateSchemaRelationalTypeResolverDecorator;

class ValidateACLDirectiveForDirectivesPrivateSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPrivateSchemaRelationalTypeResolverDecorator
{
    use NoCacheConfigurableAccessControlRelationalTypeResolverDecoratorTrait;

    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries(): array
    {
        $configurationEntries = [];
        foreach ($this->getAccessControlManager()->getDirectiveEntries() as $group => $entries) {
            $configurationEntries = array_merge(
                $configurationEntries,
                $entries
            );
        }
        return $configurationEntries;
    }
}
