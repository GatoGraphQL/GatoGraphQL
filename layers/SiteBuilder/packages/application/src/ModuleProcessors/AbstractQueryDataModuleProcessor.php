<?php

declare(strict_types=1);

namespace PoP\Application\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\QueryDataModuleProcessorTrait;

abstract class AbstractQueryDataModuleProcessor extends AbstractModuleProcessor
{
    use QueryDataModuleProcessorTrait;
}
