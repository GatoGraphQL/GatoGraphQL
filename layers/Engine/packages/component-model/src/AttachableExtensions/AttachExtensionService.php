<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

class AttachExtensionService implements AttachExtensionServiceInterface
{
    /**
     * @var array<string,array<string,string>>
     */
    protected array $classGroups = [];

    public function enqueueExtension(string $event, string $class, string $group): void
    {
        $this->classGroups[$event][$class] = $group;
    }
    public function attachExtensions(string $event): void
    {
        foreach ($this->classGroups[$event] as $class => $group) {
            $class::attach($group);
        }
    }
}
