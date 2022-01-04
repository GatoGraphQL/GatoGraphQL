<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private array $getCommentMetaEntries = [];
    private string $getCommentMetaBehavior = Behaviors::ALLOWLIST;

    public function getCommentMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::COMMENT_META_ENTRIES;
        $selfProperty = &$this->getCommentMetaEntries;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function getCommentMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::COMMENT_META_BEHAVIOR;
        $selfProperty = &$this->getCommentMetaBehavior;
        $defaultValue = Behaviors::ALLOWLIST;

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
