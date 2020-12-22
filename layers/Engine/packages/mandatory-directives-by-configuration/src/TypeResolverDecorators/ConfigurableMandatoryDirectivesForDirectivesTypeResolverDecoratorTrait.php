<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForDirectivesTrait;

trait ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait
{
    use ConfigurableMandatoryDirectivesForDirectivesTrait;

    abstract protected function getMandatoryDirectives($entryValue = null): array;

    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        foreach ($this->getEntries() as $entry) {
            $directiveResolverClass = $entry[0];
            $entryValue = $entry[1] ?? null; // this might be any value (string, array, etc) or, if not defined, null
            $directiveName = $directiveResolverClass::getDirectiveName();
            $mandatoryDirectivesForDirectives[$directiveName] = $this->getMandatoryDirectives($entryValue);
        }
        return $mandatoryDirectivesForDirectives;
    }
}
