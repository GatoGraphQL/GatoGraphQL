<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

class AttachExtensionService implements AttachExtensionServiceInterface
{
    /**
     * @var array<string,string>
     */
    protected array $classGroups = [];
    // /**
    //  * @var array<string,int|null>
    //  */
    // protected array $classPriorities = [];

    public function enqueueExtension(string $class, string $group/*, ?int $priority = null*/): void
    {
        $this->classGroups[$class] = $group;
        // $this->classPriorities[$class] = $priority;
    }
    public function attachExtensions(): void
    {
        // foreach (array_keys($this->classGroups) as $class) {
        //     $class::attach($this->classGroups[$class], $this->classPriorities[$class]);
        // }
        foreach ($this->classGroups as $class => $group) {
            $class::attach($group);
        }
    }
}
