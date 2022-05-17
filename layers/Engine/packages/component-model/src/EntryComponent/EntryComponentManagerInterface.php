<?php

declare(strict_types=1);

namespace PoP\ComponentModel\EntryComponent;

interface EntryComponentManagerInterface
{
    /**
     * Obtain the first module from which the Module Model is processed
     */
    public function getEntryComponent(): ?array;
}
