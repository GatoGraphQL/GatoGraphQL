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
        $envVariable = Environment::COMMENT_META_ENTRIES;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getCommentMetaBehavior(): string
    {
        $envVariable = Environment::COMMENT_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOWLIST;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }
}
