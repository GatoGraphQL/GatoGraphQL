<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

use PoP\Root\Services\ActivableServiceInterface;

class AttachExtensionService implements AttachExtensionServiceInterface
{
    /**
     * @var array<string,array<string,AttachableExtensionInterface[]>>
     */
    protected array $classGroups = [];

    public function enqueueExtension(string $event, string $group, AttachableExtensionInterface $extension): void
    {
        $this->classGroups[$event][$group][] = $extension;
    }
    public function attachExtensions(string $event): void
    {
        foreach (($this->classGroups[$event] ?? []) as $group => $extensions) {
            // Only attach the enabled thervices
            $extensions = array_filter(
                $extensions,
                fn (ActivableServiceInterface $extension) => $extension->isServiceEnabled()
            );
            foreach ($extensions as $extension) {
                $extension->attach($group);
            }
        }
    }
}
