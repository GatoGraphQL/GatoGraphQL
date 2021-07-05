<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class EnvironmentVariablesDataSource
{
    public const GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE = 'GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE';

    /**
     * @return array<string,string>
     */
    public function getEnvironmentVariables(): array
    {
        return [
            self::GENERATE_ARTIFACT_WITH_DOWNGRADED_CODE => false,
        ];
    }
}
