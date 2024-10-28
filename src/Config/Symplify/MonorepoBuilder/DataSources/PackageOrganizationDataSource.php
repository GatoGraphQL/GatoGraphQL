<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class PackageOrganizationDataSource
{
    public function __construct(protected string $rootDir)
    {
    }

    /**
     * @return array<string,string>
     */
    public function getPackagePathOrganizations(): array
    {
        return [
            'layers/API/packages' => 'PoP-PoPAPI',
            'layers/Backbone/packages' => 'PoPBackbone',
            'layers/CMSSchema/packages' => 'PoPCMSSchema',
            'layers/Engine/packages' => 'getpop',
            'layers/GatoGraphQLForWP/packages' => 'GatoGraphQLForWordPress',
            'layers/GatoGraphQLForWP/phpunit-packages' => 'PHPUnitForGatoGraphQL',
            'layers/GatoGraphQLForWP/phpunit-plugins' => 'PHPUnitForGatoGraphQL',
            'layers/GatoGraphQLForWP/plugins' => 'GatoGraphQLForWordPress',
            'layers/GatoGraphQLForWP/standalone-plugins' => 'GatoGraphQLForWordPress',
            'layers/GraphQLByPoP/clients' => 'GraphQLByPoP',
            'layers/GraphQLByPoP/packages' => 'GraphQLByPoP',
            'layers/Schema/packages' => 'PoPSchema',
            'layers/WPSchema/packages' => 'PoPWPSchema',
        ];
    }

    /**
     * @return array<string>
     */
    public function getPackageDirectories(): array
    {
        return array_map(
            fn (string $packagePath) => $this->rootDir . '/' . $packagePath,
            array_keys($this->getPackagePathOrganizations())
        );
    }

    /**
     * @return array<string>
     */
    public function getPackageDirectoryExcludes(): array
    {
        return [
            'gatographql/wordpress',
        ];
    }
}
