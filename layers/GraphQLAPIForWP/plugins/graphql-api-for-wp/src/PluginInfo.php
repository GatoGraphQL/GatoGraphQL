<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractPluginInfo;

class PluginInfo extends AbstractPluginInfo
{
    protected function initialize(): void
    {
        $this->values = [
            'version' => $this->plugin->getPluginVersion(),
            'file' => $this->plugin->getPluginFile(),
            'baseName' => $this->plugin->getPluginBaseName(),
            'name' => $this->plugin->getPluginName(),
            'dir' => $this->plugin->getPluginDir(),
            'url' => $this->plugin->getPluginURL(),
        ];
    }

    public function getVersion(): string
    {
        return $this->values['version'];
    }

    public function getFile(): string
    {
        return $this->values['file'];
    }

    public function getBaseName(): string
    {
        return $this->values['baseName'];
    }

    public function getName(): string
    {
        return $this->values['name'];
    }

    public function getDir(): string
    {
        return $this->values['dir'];
    }

    public function getURL(): string
    {
        return $this->values['url'];
    }
}
