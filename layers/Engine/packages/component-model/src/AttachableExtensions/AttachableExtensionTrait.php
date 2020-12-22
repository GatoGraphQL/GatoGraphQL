<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

use PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade;

trait AttachableExtensionTrait
{
    /**
     * It is represented through a static class, because the extensions work at class level, not object level
     */
    public static function getClassesToAttachTo(): array
    {
        return [];
    }

    /**
     * The priority with which to attach to the class. The higher the priority, the sooner it will be processed
     *
     * @return integer|null
     */
    public static function getPriorityToAttachClasses(): ?int
    {
        return null;
    }

    /**
     * There are 2 ways of setting a priority: either by configuration through parameter, or explicity defined in the class itself
     * The priority in the class has priority (pun intended ;))
     *
     * @param string $group
     * @param integer $priority
     * @return void
     */
    public static function attach(string $group, int $priority = 10)
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        $extensionClass = get_called_class();
        foreach ($extensionClass::getClassesToAttachTo() as $attachableClass) {
            $attachableExtensionManager->setExtensionClass(
                $attachableClass,
                $group,
                $extensionClass,
                $extensionClass::getPriorityToAttachClasses() ?? $priority
            );
        }
    }
}
