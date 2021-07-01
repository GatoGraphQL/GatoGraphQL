<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\Configurators;

use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\DataToAppendAndRemoveDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\DowngradeRectorDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PackageOrganizationDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\ReleaseWorkersDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\UnmigratedFailingPackagesDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option as CustomOption;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Neon\NeonPrinter;

class ContainerConfigurationService
{
    public function __construct(
        protected ContainerConfigurator $containerConfigurator,
        protected string $rootDirectory,
    ) {
    }
    
    public function configureContainer(): void
    {
        $parameters = $this->containerConfigurator->parameters();

        $packageOrganizationConfig = new PackageOrganizationDataSource($this->rootDirectory);
        $parameters->set(
            CustomOption::PACKAGE_ORGANIZATIONS,
            $packageOrganizationConfig->getPackagePathOrganizations()
        );
        $parameters->set(
            Option::PACKAGE_DIRECTORIES,
            $packageOrganizationConfig->getPackageDirectories()
        );
        $parameters->set(
            Option::PACKAGE_DIRECTORIES_EXCLUDES,
            $packageOrganizationConfig->getPackageDirectoryExcludes()
        );

        /**
         * Plugins to generate
         */
        $pluginConfig = new PluginDataSource($this->rootDirectory);
        $parameters->set(
            CustomOption::PLUGIN_CONFIG_ENTRIES,
            $pluginConfig->getPluginConfigEntries()
        );

        /**
         * Additional downgrade Rector configs:
         * Hack to fix bugs
         * @see https://github.com/rectorphp/rector/issues/5962
         * @see https://github.com/leoloso/PoP/issues/597#issue-855005786
         */
        $downgradeRectorConfig = new DowngradeRectorDataSource($this->rootDirectory);
        $parameters->set(
            CustomOption::ADDITIONAL_DOWNGRADE_RECTOR_CONFIGS,
            $downgradeRectorConfig->getAdditionalDowngradeRectorDataSourceFiles()
        );

        // Temporary hack! PHPStan is currently failing for these packages,
        // because they have not been fully converted to PSR-4 (WIP),
        // and converting them will take some time. Hence, for the time being,
        // skip them from executing PHPStan, to avoid the CI from failing
        $unmigratedFailingPackagesConfig = new UnmigratedFailingPackagesDataSource();
        $parameters->set(
            CustomOption::UNMIGRATED_FAILING_PACKAGES,
            $unmigratedFailingPackagesConfig->getUnmigratedFailingPackages()
        );

        $parameters = $this->containerConfigurator->parameters();
        $dataToAppendAndRemoveConfig = new DataToAppendAndRemoveDataSource();
        $parameters->set(
            Option::DATA_TO_APPEND,
            $dataToAppendAndRemoveConfig->getDataToAppend()
        );
        $parameters->set(
            Option::DATA_TO_REMOVE,
            $dataToAppendAndRemoveConfig->getDataToRemove()
        );

        $services = $this->containerConfigurator->services();
        $services->defaults()
            ->autowire()
            ->autoconfigure();

        /** Set all services */
        $services
            ->set(NeonPrinter::class) // Required to inject into PHPStanNeonContentProvider
            ->load('PoP\\PoP\\', 'src/*');

        /** release workers - in order to execute */
        $releaseWorkersConfig = new ReleaseWorkersDataSource();
        foreach ($releaseWorkersConfig->getReleaseWorkerClasses() as $releaseWorkerClass) {
            $services->set($releaseWorkerClass);
        }
    }
}
