<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

interface DataloadingEngineInterface
{
    public function getMandatoryDirectiveClasses(): array;
    public function getMandatoryDirectives(): array;
    public function addMandatoryDirective(string $directive): void;
    public function addMandatoryDirectiveClass(string $directiveClass): void;
    public function addMandatoryDirectiveClasses(array $directiveClasses): void;
}
