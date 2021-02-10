<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

class AttachExtensionService implements AttachExtensionServiceInterface
{
    /**
     * @var array<string,string>
     */
    protected array $classGroups = [];

    public function enqueueExtension(string $class, string $group): void
    {
        $this->classGroups[$class] = $group;
    }
    public function attachExtensions(): void
    {
        foreach ($this->classGroups as $class => $group) {
            $class::attach($group);
        }
    }
}
