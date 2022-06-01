<?php

declare(strict_types=1);

namespace PoP\ComponentModel\EntryComponent;

use PoP\ComponentModel\Component\Component;
interface EntryComponentManagerInterface
{
    /**
     * Obtain the first module from which the Module Model is processed
     */
    public function getEntryComponent(): ?Component;
}
