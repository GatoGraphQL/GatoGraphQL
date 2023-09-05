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
}
