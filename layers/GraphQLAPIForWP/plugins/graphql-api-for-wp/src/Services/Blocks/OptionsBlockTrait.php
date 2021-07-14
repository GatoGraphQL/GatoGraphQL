<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

/**
 * Query Execution (endpoint and persisted query) Options block
 */
trait OptionsBlockTrait
{
    /**
     * Given a bool, return its label for rendering
     */
    protected function getBooleanLabel(bool $value): string
    {
        if ($value) {
            return \__('✅ Yes', 'graphql-api');
        }
        return \__('❌ No', 'graphql-api');
    }
}
