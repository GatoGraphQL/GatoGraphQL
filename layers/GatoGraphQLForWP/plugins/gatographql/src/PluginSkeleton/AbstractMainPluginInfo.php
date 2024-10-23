<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\PluginEnvironment;

abstract class AbstractMainPluginInfo extends AbstractPluginInfo implements MainPluginInfoInterface
{
    /**
     * Don't initialize 'cache-dir' yet so the folder can be
     * customized by standalone plugins
     */
    protected function initialize(): void
    {
        $this->values = [];
    }

    /**
     * Where to store the config cache,
     * for both /container and /operational
     * (config persistent cache: component model configuration + schema)
     */
    public function getCacheDir(): string
    {
        if (!isset($this->values['cache-dir'])) {
            $this->values['cache-dir'] = PluginEnvironment::getCacheDir();
        }
        return $this->values['cache-dir'];
    }
}
