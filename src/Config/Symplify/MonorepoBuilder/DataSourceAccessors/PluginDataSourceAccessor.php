<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSourceAccessors;

use PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources\PluginDataSource;

class PluginDataSourceAccessor
{
    public function __construct(protected PluginDataSource $pluginDataSource)
    {
    }

    /**
     * @return string[]
     */
    public function getPluginMainFiles(): array
    {
        $files = [];
        foreach ($this->pluginDataSource->getPluginConfigEntries() as $pluginConfigEntry) {
            $files[] = $this->pluginDataSource->getRootDir() . '/' . $pluginConfigEntry['path'] . '/' . $pluginConfigEntry['main_file'];
        }
        return $files;
    }

    /**
     * @return string[]
     */
    public function getPluginReadmeFiles(): array
    {
        $files = [];
        foreach ($this->pluginDataSource->getPluginConfigEntries() as $pluginConfigEntry) {
            $readmeFile = $this->pluginDataSource->getRootDir() . '/' . $pluginConfigEntry['path'] . '/readme.txt';
            if (!file_exists($readmeFile)) {
                continue;
            }
            $files[] = $readmeFile;
        }
        return $files;
    }

    /**
     * @return string[]
     */
    public function getPluginNodeJSPackageDirectories(): array
    {
        $directories = [];
        $possiblePackageFolders = $this->getPossiblePackageFolders();
        foreach ($this->pluginDataSource->getPluginConfigEntries() as $pluginConfigEntry) {
            foreach ($possiblePackageFolders as $packageFolder) {
                $folder = $this->pluginDataSource->getRootDir() . '/' . $pluginConfigEntry['path'] . '/' . $packageFolder;
                if (!file_exists($folder)) {
                    continue;
                }
                $directories[] = $folder;
            }
        }
        return $directories;
    }

    /**
     * @return string[]
     */
    protected function getPossiblePackageFolders(): array
    {
        return [
            'blocks',
            'editor-scripts',
            'packages',
        ];
    }
}
