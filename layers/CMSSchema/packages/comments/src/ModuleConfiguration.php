<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function getRootCommentListDefaultLimit(): ?int
    {
        $envVariable = Environment::ROOT_COMMENT_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getCustomPostCommentOrCommentResponseListDefaultLimit(): ?int
    {
        $envVariable = Environment::CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT;
        $defaultValue = -1; // Unlimited
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getCommentListMaxLimit(): ?int
    {
        $envVariable = Environment::COMMENT_LIST_MAX_LIMIT;
        $defaultValue = -1;
        $callback = EnvironmentValueHelpers::toInt(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function treatCommentStatusAsAdminData(): bool
    {
        $envVariable = Environment::TREAT_COMMENT_STATUS_AS_ADMIN_DATA;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
