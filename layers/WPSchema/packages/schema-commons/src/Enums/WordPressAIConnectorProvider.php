<?php

declare(strict_types=1);

namespace PoPWPSchema\SchemaCommons\Enums;

/**
 * Provider IDs used by the WordPress 7.0+ Connectors API.
 *
 * @see https://make.wordpress.org/core/2026/03/18/introducing-the-connectors-api-in-wordpress-7-0/
 */
enum WordPressAIConnectorProvider: string
{
    case OPENAI = 'openai';
    case ANTHROPIC = 'anthropic';
    case GOOGLE = 'google';

    public function getServiceName(): string
    {
        return match ($this) {
            self::OPENAI => 'chatgpt',
            self::ANTHROPIC => 'claude',
            self::GOOGLE => 'gemini',
        };
    }
}
