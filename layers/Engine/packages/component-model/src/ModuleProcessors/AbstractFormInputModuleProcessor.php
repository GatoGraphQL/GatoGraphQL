<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

abstract class AbstractFormInputModuleProcessor extends AbstractQueryDataModuleProcessor implements FormComponentModuleProcessorInterface
{
    use FormInputModuleProcessorTrait;
}
