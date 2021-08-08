<?php

declare(strict_types=1);

namespace PoP\Application\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\QueryDataModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\QueryDataModuleProcessorTrait;

abstract class AbstractQueryDataModuleProcessor extends AbstractModuleProcessor implements QueryDataModuleProcessorInterface
{
    use QueryDataModuleProcessorTrait;
}
