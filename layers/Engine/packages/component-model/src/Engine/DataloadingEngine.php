<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\Root\Registries\AbstractServiceDefinitionIDRegistry;

class DataloadingEngine extends AbstractServiceDefinitionIDRegistry implements DataloadingEngineInterface
{
    /**
     * @var string[]
     */
    protected array $mandatoryRootDirectives = [];

    public function getMandatoryDirectives(): array
    {
        return $this->mandatoryRootDirectives;
    }
}
