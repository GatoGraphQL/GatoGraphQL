<?php

declare(strict_types=1);

namespace PoP\EngineWP;

use PoP\Root\Component\AbstractComponentInfo;

class ComponentInfo extends AbstractComponentInfo
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
