<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ModuleProcessors;
use PoP\ComponentModel\ModuleProcessors\QueryDataModuleProcessorTrait;

abstract class AbstractQueryDataModuleProcessor extends AbstractModuleProcessor
{
    use QueryDataModuleProcessorTrait;
}
