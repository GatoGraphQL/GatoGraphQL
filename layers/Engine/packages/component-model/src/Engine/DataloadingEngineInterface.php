<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

interface DataloadingEngineInterface
{
    public function getMandatoryDirectiveClasses(): array;
    public function getMandatoryDirectives(): array;
    public function addMandatoryDirectiveClass(string $directiveClass): void;
}
