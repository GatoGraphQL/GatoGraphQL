<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface ModuleDecoratorProcessorInterface
{
    public function getAllSubmodules(array $module): array;
}
