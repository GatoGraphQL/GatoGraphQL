<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class PackageOrganizationDataSource
{
    public function __construct(protected string $rootDir)
    {
    }

    /**
     * @return array<string, string>
     */
    public function getPackagePathOrganizations(): array
    {
        return [
            'layers/API/packages' => 'PoP-PoPAPI',
            'layers/Backbone/packages' => 'PoPBackbone',
            'layers/CMSSchema/packages' => 'PoPCMSSchema',
            'layers/Engine/packages' => 'getpop',
            'layers/GraphQLAPIForWP/packages' => 'GraphQLAPI',
            'layers/GraphQLAPIForWP/phpunit-packages' => 'PHPUnitForGraphQLAPI',
            'layers/GraphQLAPIForWP/phpunit-plugins' => 'PHPUnitForGraphQLAPI',
            'layers/GraphQLAPIForWP/plugins' => 'GraphQLAPI',
            'layers/GraphQLByPoP/clients' => 'GraphQLByPoP',
            'layers/GraphQLByPoP/packages' => 'GraphQLByPoP',
            'layers/Schema/packages' => 'PoPSchema',
            'layers/SiteBuilder/packages' => 'getpop',
            'layers/Wassup/packages' => 'PoPSites-Wassup',
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
            'graphql-api-for-wp/wordpress',
        ];
    }
}
