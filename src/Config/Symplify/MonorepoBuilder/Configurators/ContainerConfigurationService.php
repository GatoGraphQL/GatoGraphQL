<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\Configurators;

use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\AdditionalIntegrationTestPluginsDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\DataToAppendAndRemoveDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\DowngradeRectorDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\EnvironmentVariablesDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\InstaWPConfigDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\MonorepoSplitPackageDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PHPStanDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PackageOrganizationDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\ReleaseWorkersDataSource;
use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\SkipDowngradeTestPathsDataSource;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option as CustomOption;
use PoP\PoP\Monorepo\MonorepoMetadata;
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

        $parameters->set(Option::DEFAULT_BRANCH_NAME, MonorepoMetadata::GIT_BASE_BRANCH);

        /**
         * Packages handled by the monorepo
         */
        if ($packageOrganizationConfig = $this->getPackageOrganizationDataSource()) {
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
         * Packages not to do the monorepo split
         */
        if ($skipMonorepoSplitPackageConfig = $this->getMonorepoSplitPackageDataSource()) {
            $parameters->set(
                CustomOption::SKIP_MONOREPO_SPLIT_PACKAGE_PATHS,
                $skipMonorepoSplitPackageConfig->getSkipMonorepoSplitPackagePaths()
            );
        }

        /**
         * Plugins to generate
         */
        if ($pluginConfig = $this->getPluginDataSource()) {
            $parameters->set(
                CustomOption::PLUGIN_CONFIG_ENTRIES,
                $pluginConfig->getPluginConfigEntries()
            );
        }

        /**
         * Skip files from testing for downgrades
         */
        if ($skipDowngradeTestFilesConfig = $this->getSkipDowngradeTestPathsDataSource()) {
            $parameters->set(
                CustomOption::SKIP_DOWNGRADE_TEST_FILES,
                $skipDowngradeTestFilesConfig->getSkipDowngradeTestPaths()
            );
        }

        /**
         * Additional downgrade Rector configs:
         * Hack to fix bugs
         * @see https://github.com/rectorphp/rector/issues/5962
         * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/597#issue-855005786
         */
        if ($downgradeRectorConfig = $this->getDowngradeRectorDataSource()) {
            $parameters->set(
                CustomOption::ADDITIONAL_DOWNGRADE_RECTOR_BEFORE_CONFIGS,
                $downgradeRectorConfig->getAdditionalDowngradeRectorBeforeFiles()
            );
            $parameters->set(
                CustomOption::ADDITIONAL_DOWNGRADE_RECTOR_AFTER_CONFIGS,
                $downgradeRectorConfig->getAdditionalDowngradeRectorAfterFiles()
            );
        }

        /**
         * Additional plugins to install in the webserver (eg: InstaWP)
         * for executing integration tests
         */
        if ($additionalIntegrationTestPluginsConfig = $this->getAdditionalIntegrationTestPluginsDataSource()) {
            $parameters->set(
                CustomOption::ADDITIONAL_INTEGRATION_TEST_PLUGINS,
                $additionalIntegrationTestPluginsConfig->getAdditionalIntegrationTestPlugins()
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
        if ($phpStanConfig = $this->getPHPStanDataSource()) {
            $parameters->set(
                CustomOption::LEVEL,
                $phpStanConfig->getLevel()
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
         * InstaWP config
         */
        if ($instaWPConfig = $this->getInstaWPConfigDataSource()) {
            $parameters->set(
                CustomOption::INSTAWP_CONFIG_ENTRIES,
                $instaWPConfig->getInstaWPConfigEntries()
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

    protected function getMonorepoSplitPackageDataSource(): ?MonorepoSplitPackageDataSource
    {
        return new MonorepoSplitPackageDataSource($this->rootDirectory);
    }

    protected function getPluginDataSource(): ?PluginDataSource
    {
        return new PluginDataSource($this->rootDirectory);
    }

    protected function getInstaWPConfigDataSource(): ?InstaWPConfigDataSource
    {
        return new InstaWPConfigDataSource($this->rootDirectory);
    }

    protected function getSkipDowngradeTestPathsDataSource(): ?SkipDowngradeTestPathsDataSource
    {
        return new SkipDowngradeTestPathsDataSource($this->rootDirectory);
    }

    protected function getDowngradeRectorDataSource(): ?DowngradeRectorDataSource
    {
        return new DowngradeRectorDataSource($this->rootDirectory);
    }

    protected function getAdditionalIntegrationTestPluginsDataSource(): ?AdditionalIntegrationTestPluginsDataSource
    {
        return new AdditionalIntegrationTestPluginsDataSource($this->rootDirectory);
    }

    protected function getEnvironmentVariablesDataSource(): ?EnvironmentVariablesDataSource
    {
        return new EnvironmentVariablesDataSource();
    }

    protected function getPHPStanDataSource(): ?PHPStanDataSource
    {
        return new PHPStanDataSource();
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
            ->load('PoP\\PoP\\Config\\', $this->rootDirectory . '/src/Config/*')
            ->load('PoP\\PoP\\Extensions\\', $this->rootDirectory . '/src/Extensions/*')
            ->load('PoP\\PoP\\Monorepo\\', $this->rootDirectory . '/src/Monorepo/*');
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
