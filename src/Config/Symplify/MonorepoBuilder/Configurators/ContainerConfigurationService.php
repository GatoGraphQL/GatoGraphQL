<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\Configurators;

use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\DataToAppendAndRemoveDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\DowngradeRectorDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\EnvironmentVariablesDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PackageOrganizationDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\ReleaseWorkersDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\UnmigratedFailingPackagesDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option as CustomOption;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
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

        /**
         * Packages handled by the monorepo
         */
        if ($packageOrganizationConfig = $this->getPackageOrganizationDataSource($this->rootDirectory)) {
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
        }

        /**
         * Plugins to generate
         */
        if ($pluginConfig = $this->getPluginDataSource($this->rootDirectory)) {
            $parameters->set(
                CustomOption::PLUGIN_CONFIG_ENTRIES,
                $pluginConfig->getPluginConfigEntries()
            );
        }

        /**
         * Additional downgrade Rector configs:
         * Hack to fix bugs
         * @see https://github.com/rectorphp/rector/issues/5962
         * @see https://github.com/leoloso/PoP/issues/597#issue-855005786
         */
        if ($downgradeRectorConfig = $this->getDowngradeRectorDataSource($this->rootDirectory)) {
            $parameters->set(
                CustomOption::ADDITIONAL_DOWNGRADE_RECTOR_CONFIGS,
                $downgradeRectorConfig->getAdditionalDowngradeRectorDataSourceFiles()
            );
        }

        /**
         * Environment variables
         */
        if ($environmentVariablesConfig = $this->getEnvironmentVariablesDataSource()) {
            $parameters->set(
                CustomOption::ENVIRONMENT_VARIABLES,
                $environmentVariablesConfig->getEnvironmentVariables()
            );
        }

        /**
         * Temporary hack! PHPStan is currently failing for these packages,
         * because they have not been fully converted to PSR-4 (WIP),
         * and converting them will take some time. Hence, for the time being,
         * skip them from executing PHPStan, to avoid the CI from failing
         */
        if ($unmigratedFailingPackagesConfig = $this->getUnmigratedFailingPackagesDataSource()) {
            $parameters->set(
                CustomOption::UNMIGRATED_FAILING_PACKAGES,
                $unmigratedFailingPackagesConfig->getUnmigratedFailingPackages()
            );
            $parameters->set(
                CustomOption::LEVEL,
                $unmigratedFailingPackagesConfig->getLevel()
            );
        }

        /**
         * Libraries that must always be required (or removed) in composer.json
         */
        if ($dataToAppendAndRemoveConfig = $this->getDataToAppendAndRemoveDataSource()) {
            $parameters->set(
                Option::DATA_TO_APPEND,
                $dataToAppendAndRemoveConfig->getDataToAppend()
            );
            $parameters->set(
                Option::DATA_TO_REMOVE,
                $dataToAppendAndRemoveConfig->getDataToRemove()
            );
        }

        /**
         * Configure services
         */
        $services = $this->containerConfigurator->services();
        $services->defaults()
            ->autowire()
            ->autoconfigure();

        /**
         * Set all custom services
         */
        $this->setServices($services);
    }

    protected function getPackageOrganizationDataSource(): ?PackageOrganizationDataSource
    {
        return new PackageOrganizationDataSource($this->rootDirectory);
    }
    
    protected function getPluginDataSource(): ?PluginDataSource
    {
        return new PluginDataSource($this->rootDirectory);
    }
    
    protected function getDowngradeRectorDataSource(): ?DowngradeRectorDataSource
    {
        return new DowngradeRectorDataSource($this->rootDirectory);
    }
    
    protected function getEnvironmentVariablesDataSource(): ?EnvironmentVariablesDataSource
    {
        return new EnvironmentVariablesDataSource();
    }
    
    protected function getUnmigratedFailingPackagesDataSource(): ?UnmigratedFailingPackagesDataSource
    {
        return new UnmigratedFailingPackagesDataSource();
    }
    
    protected function getDataToAppendAndRemoveDataSource(): ?DataToAppendAndRemoveDataSource
    {
        return new DataToAppendAndRemoveDataSource();
    }
    
    protected function getReleaseWorkersDataSource(): ?ReleaseWorkersDataSource
    {
        return new ReleaseWorkersDataSource();
    }

    protected function setServices(ServicesConfigurator $services): void
    {
        /**
         * Set all custom services
         */
        $this->setCustomServices($services);

        /**
         * Release workers
         */
        $this->setReleaseWorkerServices($services);
    }

    protected function setCustomServices(ServicesConfigurator $services): void
    {
        $services
            ->set(NeonPrinter::class) // Required to inject into PHPStanNeonContentProvider
            ->load('PoP\\PoP\\', $this->rootDirectory . '/src/*');
    }

    protected function setReleaseWorkerServices(ServicesConfigurator $services): void
    {
        /**
         * Release workers - in order to execute
         */
        if ($releaseWorkersConfig = $this->getReleaseWorkersDataSource()) {
            foreach ($releaseWorkersConfig->getReleaseWorkerClasses() as $releaseWorkerClass) {
                $services->set($releaseWorkerClass);
            }
        }
    }
}
