<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\ConditionalOnModule\CacheControl\Services\CacheControlForAccessControlManagerInterface;
use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator;

/**
 * Add IFTTT on @cacheControl(maxAge: 0) on all rules that
 * have been applied some ACL rule
 */
class NoCacheValidateACLDirectiveForFieldsPrivateSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator
{
    use NoCacheConfigurableAccessControlRelationalTypeResolverDecoratorTrait;

    private ?CacheControlForAccessControlManagerInterface $cacheControlForAccessControlManager = null;

    final public function setCacheControlForAccessControlManager(CacheControlForAccessControlManagerInterface $cacheControlForAccessControlManager): void
    {
        $this->cacheControlForAccessControlManager = $cacheControlForAccessControlManager;
    }
    final protected function getCacheControlForAccessControlManager(): CacheControlForAccessControlManagerInterface
    {
        if ($this->cacheControlForAccessControlManager === null) {
            /** @var CacheControlForAccessControlManagerInterface */
            $cacheControlForAccessControlManager = $this->instanceManager->getInstance(CacheControlForAccessControlManagerInterface::class);
            $this->cacheControlForAccessControlManager = $cacheControlForAccessControlManager;
        }
        return $this->cacheControlForAccessControlManager;
    }

    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries(): array
    {
        $groupEntries = $this->getAccessControlManager()->getFieldEntries();
        $supportingCacheControlGroups = $this->getCacheControlForAccessControlManager()->getSupportingCacheControlAccessControlGroups();
        $configurationEntries = [];
        foreach ($groupEntries as $group => $entries) {
            /**
             * AccessControlGroups::DISABLED does have CacheControl!
             */
            if (in_array($group, $supportingCacheControlGroups)) {
                continue;
            }
            $configurationEntries = array_merge(
                $configurationEntries,
                $entries
            );
        }
        return $configurationEntries;
    }
}
