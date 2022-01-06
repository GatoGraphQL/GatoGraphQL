<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    /**
     * @return string[]
     */
    public function getCommentMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::COMMENT_META_ENTRIES;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getCommentMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::COMMENT_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOWLIST;

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }
}
