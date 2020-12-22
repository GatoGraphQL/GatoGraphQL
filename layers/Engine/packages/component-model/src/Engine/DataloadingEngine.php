<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

class DataloadingEngine implements DataloadingEngineInterface
{
    /**
     * @var string[]
     */
    protected array $mandatoryRootDirectiveClasses = [];
    /**
     * @var string[]
     */
    protected array $mandatoryRootDirectives = [];

    public function getMandatoryDirectiveClasses(): array
    {
        return $this->mandatoryRootDirectiveClasses;
    }
    public function getMandatoryDirectives(): array
    {
        return $this->mandatoryRootDirectives;
    }

    public function addMandatoryDirectiveClass(string $directiveClass): void
    {
        $this->mandatoryRootDirectiveClasses[] = $directiveClass;
    }
    public function addMandatoryDirective(string $directive): void
    {
        $this->mandatoryRootDirectives[] = $directive;
    }

    public function addMandatoryDirectiveClasses(array $directiveClasses): void
    {
        $this->mandatoryRootDirectiveClasses = array_merge(
            $this->mandatoryRootDirectiveClasses,
            $directiveClasses
        );
    }
    public function addMandatoryDirectives(array $directives): void
    {
        $this->mandatoryRootDirectives = array_merge(
            $this->mandatoryRootDirectives,
            $directives
        );
    }
}
