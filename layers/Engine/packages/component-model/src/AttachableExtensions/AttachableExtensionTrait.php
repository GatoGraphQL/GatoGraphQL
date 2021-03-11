<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

use PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade;

trait AttachableExtensionTrait
{
    /**
     * It is represented through a static class, because the extensions work at class level, not object level
     */
    public function getClassesToAttachTo(): array
    {
        return [];
    }

    /**
     * The priority with which to attach to the class. The higher the priority, the sooner it will be processed
     */
    public function getPriorityToAttachToClasses(): int
    {
        return 10;
    }

    /**
     * There are 2 ways of setting a priority: either by configuration through parameter, or explicity defined in the class itself
     * The priority in the class has priority (pun intended ;))
     */
    public function attach(string $group): void
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        foreach ($this->getClassesToAttachTo() as $attachableClass) {
            $attachableExtensionManager->attachExtensionToClass(
                $attachableClass,
                $group,
                $this
            );
        }
    }
}
