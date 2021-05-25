<?php

declare(strict_types=1);

namespace PoP\Engine\Engine;

interface EngineInterface extends \PoP\ComponentModel\Engine\EngineInterface
{
    public function outputResponse(): void;
}
