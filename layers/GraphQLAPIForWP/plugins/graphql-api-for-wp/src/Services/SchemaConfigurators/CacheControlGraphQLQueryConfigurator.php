<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\CacheControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\ComponentModel\Misc\GeneralUtils;

class CacheControlGraphQLQueryConfigurator extends AbstractGraphQLQueryConfigurator
{
    private ?CacheControlBlock $cacheControlBlock = null;
    private ?BlockHelpers $blockHelpers = null;
    private ?CacheControlManagerInterface $cacheControlManager = null;

    final public function setCacheControlBlock(CacheControlBlock $cacheControlBlock): void
    {
        $this->cacheControlBlock = $cacheControlBlock;
    }
    final protected function getCacheControlBlock(): CacheControlBlock
    {
        return $this->cacheControlBlock ??= $this->instanceManager->getInstance(CacheControlBlock::class);
    }
    final public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        return $this->blockHelpers ??= $this->instanceManager->getInstance(BlockHelpers::class);
    }
    final public function setCacheControlManager(CacheControlManagerInterface $cacheControlManager): void
    {
        $this->cacheControlManager = $cacheControlManager;
    }
    final protected function getCacheControlManager(): CacheControlManagerInterface
    {
        return $this->cacheControlManager ??= $this->instanceManager->getInstance(CacheControlManagerInterface::class);
    }

    public function isServiceEnabled(): bool
    {
        // Only execute for GET operations
        if (App::server('REQUEST_METHOD') !== 'GET') {
            return false;
        }

        return parent::isServiceEnabled();
    }

    public function getEnablingModule(): ?string
    {
        return PerformanceFunctionalityModuleResolver::CACHE_CONTROL;
    }

    /**
     * Extract the configuration items defined in the CPT,
     * and inject them into the service as to take effect in the current GraphQL query
     */
    protected function doExecuteSchemaConfiguration(int $cclPostID): void
    {
        $cclBlockItems = $this->getBlockHelpers()->getBlocksOfTypeFromCustomPost(
            $cclPostID,
            $this->getCacheControlBlock()
        );
        // The "Cache Control" type contains the fields/directives and the max-age
        foreach ($cclBlockItems as $cclBlockItem) {
            $maxAge = $cclBlockItem['attrs'][CacheControlBlock::ATTRIBUTE_NAME_CACHE_CONTROL_MAX_AGE] ?? null;
            if (!is_null($maxAge) && $maxAge >= 0) {
                // Extract the saved fields
                if ($typeFields = $cclBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_TYPE_FIELDS] ?? null) {
                    if (
                        $entriesForFields = GeneralUtils::arrayFlatten(
                            array_map(
                                fn ($selectedField) => $this->getEntriesFromField($selectedField, $maxAge),
                                $typeFields
                            )
                        )
                    ) {
                        $this->getCacheControlManager()->addEntriesForFields(
                            $entriesForFields
                        );
                    }
                }

                // Extract the saved directives
                if ($directives = $cclBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_DIRECTIVES] ?? null) {
                    if (
                        $entriesForDirectives = GeneralUtils::arrayFlatten(array_filter(
                            array_map(
                                fn ($selectedDirective) => $this->getEntriesFromDirective($selectedDirective, $maxAge),
                                $directives
                            )
                        ))
                    ) {
                        $this->getCacheControlManager()->addEntriesForDirectives(
                            $entriesForDirectives
                        );
                    }
                }
            }
        }
    }
}
