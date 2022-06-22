<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

abstract class AbstractFilterDataComponentProcessor extends AbstractComponentProcessor implements FilterDataComponentProcessorInterface
{
    use FilterDataComponentProcessorTrait;
}
