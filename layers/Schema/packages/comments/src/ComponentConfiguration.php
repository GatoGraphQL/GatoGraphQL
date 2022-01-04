<?php

declare(strict_types=1);

namespace PoPSchema\Comments;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private ?int $getRootCommentListDefaultLimit = 10;
    private ?int $getCustomPostCommentOrCommentResponseListDefaultLimit = -1;
    private ?int $getCommentListMaxLimit = -1;
    private bool $treatCommentStatusAsAdminData = true;

    public function getRootCommentListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::ROOT_COMMENT_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getRootCommentListDefaultLimit;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function getCustomPostCommentOrCommentResponseListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT;
        $selfProperty = &$this->getCustomPostCommentOrCommentResponseListDefaultLimit;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function getCommentListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::COMMENT_LIST_MAX_LIMIT;
        $selfProperty = &$this->getCommentListMaxLimit;
        $defaultValue = -1;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function treatCommentStatusAsAdminData(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_COMMENT_STATUS_AS_ADMIN_DATA;
        $selfProperty = &$this->treatCommentStatusAsAdminData;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
