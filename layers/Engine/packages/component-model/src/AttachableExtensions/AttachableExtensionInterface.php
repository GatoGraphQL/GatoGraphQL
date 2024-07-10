<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

use PoP\Root\Services\ServiceInterface;

interface AttachableExtensionInterface extends ServiceInterface
{
    /**
     * It is represented through a static class, because the extensions work at class level, not object level
     *
     * @return string[]
     */
    public function getClassesToAttachTo(): array;

    /**
     * The priority with which to attach to the class. The higher the priority, the sooner it will be processed
     */
    public function getPriorityToAttachToClasses(): int;

    /**
     * There are 2 ways of setting a priority: either by configuration through parameter, or explicitly defined in the class itself
     * The priority in the class has priority (pun intended ;))
     */
    public function attach(string $group): void;
}
