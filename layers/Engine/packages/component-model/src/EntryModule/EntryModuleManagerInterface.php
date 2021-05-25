<?php

declare(strict_types=1);

namespace PoP\ComponentModel\EntryModule;

interface EntryModuleManagerInterface
{
    /**
     * Obtain the first module from which the Component Model is processed
     */
    public function getEntryModule(): ?array;
}
