<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

abstract class AbstractFilterDataModuleProcessor extends AbstractModuleProcessor implements FilterDataModuleProcessorInterface
{
    use FilterDataModuleProcessorTrait;
}
