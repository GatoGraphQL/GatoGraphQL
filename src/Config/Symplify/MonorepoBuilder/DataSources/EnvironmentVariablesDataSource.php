<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class EnvironmentVariablesDataSource
{
    public final const GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE = 'GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE';
    public final const RETENTION_DAYS_FOR_GENERATED_PLUGINS = 'RETENTION_DAYS_FOR_GENERATED_PLUGINS';
    public final const INSTAWP_INSTANCE_SLEEPING_TIME = 'INSTAWP_INSTANCE_SLEEPING_TIME';

    /**
     * @return array<string,string>
     */
    public function getEnvironmentVariables(): array
    {
        return [
            self::GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE => false,
            self::RETENTION_DAYS_FOR_GENERATED_PLUGINS => 30,
            self::INSTAWP_INSTANCE_SLEEPING_TIME => 120,
        ];
    }
}
