<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

abstract class AbstractDataloadModuleProcessor extends AbstractQueryDataModuleProcessor implements DataloadingModuleInterface
{
    use DataloadModuleProcessorTrait;
}
