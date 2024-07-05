<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\BlockAccessors;

use GatoGraphQL\GatoGraphQL\AppObjects\BlockAttributes\PersistedQueryEndpointGraphiQLBlockAttributes;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use GatoGraphQL\GatoGraphQL\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Variables\VariableManagerInterface;
use PoP\Root\Services\BasicServiceTrait;
use WP_Post;

class PersistedQueryEndpointGraphiQLBlockAccessor
{
    use BasicServiceTrait;

    private ?BlockHelpers $blockHelpers = null;
    private ?PersistedQueryEndpointGraphiQLBlock $persistedQueryEndpointGraphiQLBlock = null;
    private ?VariableManagerInterface $variableManager = null;

    final public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        if ($this->blockHelpers === null) {
            /** @var BlockHelpers */
            $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
            $this->blockHelpers = $blockHelpers;
        }
        return $this->blockHelpers;
    }
    final public function setPersistedQueryEndpointGraphiQLBlock(PersistedQueryEndpointGraphiQLBlock $persistedQueryEndpointGraphiQLBlock): void
    {
        $this->persistedQueryEndpointGraphiQLBlock = $persistedQueryEndpointGraphiQLBlock;
    }
    final protected function getPersistedQueryEndpointGraphiQLBlock(): PersistedQueryEndpointGraphiQLBlock
    {
        if ($this->persistedQueryEndpointGraphiQLBlock === null) {
            /** @var PersistedQueryEndpointGraphiQLBlock */
            $persistedQueryEndpointGraphiQLBlock = $this->instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);
            $this->persistedQueryEndpointGraphiQLBlock = $persistedQueryEndpointGraphiQLBlock;
        }
        return $this->persistedQueryEndpointGraphiQLBlock;
    }
    final public function setVariableManager(VariableManagerInterface $variableManager): void
    {
        $this->variableManager = $variableManager;
    }
    final protected function getVariableManager(): VariableManagerInterface
    {
        if ($this->variableManager === null) {
            /** @var VariableManagerInterface */
            $variableManager = $this->instanceManager->getInstance(VariableManagerInterface::class);
            $this->variableManager = $variableManager;
        }
        return $this->variableManager;
    }

    /**
     * Extract the Persisted Query Options block attributes from the post
     */
    public function getAttributes(WP_Post $post): ?PersistedQueryEndpointGraphiQLBlockAttributes
    {
        $graphiQLBlock = $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
            $post,
            $this->getPersistedQueryEndpointGraphiQLBlock()
        );
        // If there is either 0 or more than 1, return nothing
        if ($graphiQLBlock === null) {
            return null;
        }

        $query = $graphiQLBlock['attrs'][PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_QUERY] ?? '';
        /**
         * Variables is saved as a string, convert to array.
         *
         * Watch out! If the variables have a wrong format,
         * eg: with an additional trailing comma, such as this:
         *
         *   {
         *     "limit": 3,
         *   }
         *
         * Then doing `json_decode` will return NULL
         */
        $variables = $graphiQLBlock['attrs'][PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES] ?? null;
        if ($variables !== null) {
            $variables = json_decode($variables, true) ?? [];
        } else {
            $variables = [];
        }

        /**
         * Convert arrays to objects in the variables JSON entries.
         * 
         * For instance, storing this JSON:
         * 
         *   {
         *     "languageMapping": {
         *       "nb": "no"
         *     }
         *   }
         * 
         * ...must be interpreted as object, not array
         */
        $variables = $this->getVariableManager()->recursivelyConvertVariableEntriesFromArrayToObject($variables);

        return new PersistedQueryEndpointGraphiQLBlockAttributes(
            // Remove whitespaces so it counts as an empty query,
            // so it keeps iterating upwards to get the ancestor query
            // in `getGraphQLQueryPostAttributes`
            trim($query),
            $variables,
        );
    }
}
