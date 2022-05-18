<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

abstract class AbstractDataloadComponentProcessor extends AbstractQueryDataComponentProcessor implements DataloadingModuleInterface
{
    use DataloadComponentProcessorTrait;
}
