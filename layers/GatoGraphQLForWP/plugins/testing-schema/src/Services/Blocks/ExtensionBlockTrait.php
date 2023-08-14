<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\Services\Blocks;

use GatoGraphQL\TestingSchema\Services\Scripts\ExtensionScriptTrait;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (Gato GraphQL)
 */
trait ExtensionBlockTrait
{
    use ExtensionScriptTrait;

    protected function getBlockNamespace(): string
    {
        return 'gatographql';
    }
}
