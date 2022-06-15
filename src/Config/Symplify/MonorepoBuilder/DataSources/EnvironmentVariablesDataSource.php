<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class EnvironmentVariablesDataSource
{
    public final const GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE = 'GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE';
    public final const RETENTION_DAYS_FOR_GENERATED_PLUGINS = 'RETENTION_DAYS_FOR_GENERATED_PLUGINS';
    public final const INSTAWP_TEMPLATE_SLUG = 'INSTAWP_TEMPLATE_SLUG';
    public final const INSTAWP_REPO_ID = 'INSTAWP_REPO_ID';

    /**
     * @return array<string,string>
     */
    public function getEnvironmentVariables(): array
    {
        return [
            self::GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE => false,
            self::RETENTION_DAYS_FOR_GENERATED_PLUGINS => 30,
            self::INSTAWP_TEMPLATE_SLUG => 'integration-tests',
            self::INSTAWP_REPO_ID => 30,
        ];
    }
}
