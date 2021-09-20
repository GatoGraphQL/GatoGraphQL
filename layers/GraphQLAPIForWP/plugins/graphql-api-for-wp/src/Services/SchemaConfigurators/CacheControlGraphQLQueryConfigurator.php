<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\CacheControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\AbstractGraphQLQueryConfigurator;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\Hooks\HooksAPIInterface;

class CacheControlGraphQLQueryConfigurator extends AbstractGraphQLQueryConfigurator
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        TypeRegistryInterface $typeRegistry,
        DirectiveRegistryInterface $directiveRegistry,
        protected CacheControlBlock $cacheControlBlock,
        protected BlockHelpers $blockHelpers,
        protected CacheControlManagerInterface $cacheControlManager,
    ) {
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $moduleRegistry,
            $typeRegistry,
            $directiveRegistry,
        );
    }

    public function isServiceEnabled(): bool
    {
        // Only execute for GET operations
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
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
        $cclBlockItems = $this->blockHelpers->getBlocksOfTypeFromCustomPost(
            $cclPostID,
            $this->cacheControlBlock
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
                        $this->cacheControlManager->addEntriesForFields(
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
                        $this->cacheControlManager->addEntriesForDirectives(
                            $entriesForDirectives
                        );
                    }
                }
            }
        }
    }
}
