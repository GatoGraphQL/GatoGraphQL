<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\AbstractQueryDataModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;

abstract class AbstractFormInputModuleProcessor extends AbstractQueryDataModuleProcessor implements FormComponentModuleProcessorInterface
{
    use FormInputModuleProcessorTrait;
}
