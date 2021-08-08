<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

abstract class AbstractQueryDataModuleProcessor extends AbstractModuleProcessor implements QueryDataModuleProcessorInterface
{
    use QueryDataModuleProcessorTrait;
}
