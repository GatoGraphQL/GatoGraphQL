<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

use PoP\PoP\Monorepo\MonorepoMetadata;

class EnvironmentVariablesDataSource
{
    public final const GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE = 'GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE';
    public final const RETENTION_DAYS_FOR_GENERATED_PLUGINS = 'RETENTION_DAYS_FOR_GENERATED_PLUGINS';
    public final const INSTAWP_INSTANCE_SLEEPING_TIME = 'INSTAWP_INSTANCE_SLEEPING_TIME';
    public final const GIT_BASE_BRANCH = 'GIT_BASE_BRANCH';
    public final const GIT_USER_NAME = 'GIT_USER_NAME';
    public final const GIT_USER_EMAIL = 'GIT_USER_EMAIL';

    /**
     * @return array<string,string>
     */
    public function getEnvironmentVariables(): array
    {
        return [
            self::GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE => false,
            self::RETENTION_DAYS_FOR_GENERATED_PLUGINS => 30,
            self::INSTAWP_INSTANCE_SLEEPING_TIME => 120,
            self::GIT_BASE_BRANCH => MonorepoMetadata::GIT_BASE_BRANCH,
            self::GIT_USER_NAME => MonorepoMetadata::GIT_USER_NAME,
            self::GIT_USER_EMAIL => MonorepoMetadata::GIT_USER_EMAIL,
        ];
    }
}
