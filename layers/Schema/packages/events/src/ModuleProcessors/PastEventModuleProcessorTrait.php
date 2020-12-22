<?php

declare(strict_types=1);

namespace PoPSchema\Events\ModuleProcessors;

trait PastEventModuleProcessorTrait
{
    protected function addPastEventImmutableDataloadQueryArgs(array &$query): void
    {
        if (!isset($query['scope'])) {
            $query['scope'] = 'past';
        }

        if (!isset($query['order'])) {
            $query['order'] = 'DESC';
        }
    }
}
