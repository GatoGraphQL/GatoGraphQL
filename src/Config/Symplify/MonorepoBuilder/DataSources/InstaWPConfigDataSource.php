<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class InstaWPConfigDataSource
{
    public function __construct(protected string $rootDir)
    {
    }

    /**
     * @return array<array<mixed>
     */
    public function getInstaWPConfigEntries(): array
    {
        return [
            [
                'templateSlug' => 'integration-tests',
                'repoID' => 30,
            ],
        ];
    }
}
