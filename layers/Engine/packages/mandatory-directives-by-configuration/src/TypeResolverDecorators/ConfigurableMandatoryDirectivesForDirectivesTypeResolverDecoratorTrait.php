<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForDirectivesTrait;

trait ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait
{
    use ConfigurableMandatoryDirectivesForDirectivesTrait;

    abstract protected function getMandatoryDirectives(mixed $entryValue = null): array;

    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $mandatoryDirectivesForDirectives = [];
        foreach ($this->getEntries() as $entry) {
            $directiveResolverClass = $entry[0];
            $entryValue = $entry[1] ?? null; // this might be any value (string, array, etc) or, if not defined, null
            /** @var DirectiveResolverInterface */
            $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
            $directiveName = $directiveResolver->getDirectiveName();
            $mandatoryDirectivesForDirectives[$directiveName] = $this->getMandatoryDirectives($entryValue);
        }
        return $mandatoryDirectivesForDirectives;
    }
}
