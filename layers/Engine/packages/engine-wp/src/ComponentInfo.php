<?php

declare(strict_types=1);

namespace PoP\EngineWP;

use PoP\Root\Module\AbstractModuleInfo;

class ModuleInfo extends AbstractModuleInfo
{
    protected function initialize(): void
    {
        $this->values = [
            'templates-dir' => dirname(__DIR__) . '/templates',
        ];
    }

    public function getTemplatesDir(): string
    {
        return $this->values['templates-dir'];
    }
}
