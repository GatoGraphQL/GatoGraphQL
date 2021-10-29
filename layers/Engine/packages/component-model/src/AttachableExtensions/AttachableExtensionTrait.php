<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Root\Services\ServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

trait AttachableExtensionTrait
{
    use ServiceTrait;
    use BasicServiceTrait;

    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;

    public function setAttachableExtensionManager(AttachableExtensionManagerInterface $attachableExtensionManager): void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        return $this->attachableExtensionManager ??= $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }

    /**
     * It is represented through a static class, because the extensions work at class level, not object level
     */
    abstract public function getClassesToAttachTo(): array;

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
        foreach ($this->getClassesToAttachTo() as $attachableClass) {
            $this->getAttachableExtensionManager()->attachExtensionToClass(
                $attachableClass,
                $group,
                $this
            );
        }
    }
}
