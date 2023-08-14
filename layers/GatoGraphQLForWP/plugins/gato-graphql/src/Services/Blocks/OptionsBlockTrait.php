<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeValues;

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
            return \__('✅ Yes', 'gatographql');
        }
        return \__('❌ No', 'gatographql');
    }

    /**
     * @return array<string,string>
     */
    protected function getEnabledDisabledLabels(): array
    {
        return [
            BlockAttributeValues::ENABLED => \__('✅ Yes', 'gatographql'),
            BlockAttributeValues::DISABLED => \__('❌ No', 'gatographql'),
        ];
    }
}
