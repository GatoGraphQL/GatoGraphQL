<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator;

class ValidateDoesVisitorComeFromAllowedIPForFieldsPrivateSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator
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
