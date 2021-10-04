<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForDirectivesTrait;
use Symfony\Contracts\Service\Attribute\Required;

trait ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait
{
    use ConfigurableMandatoryDirectivesForDirectivesTrait;

    protected InstanceManagerInterface $instanceManager;

    #[Required]
    public function autowireConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait(
        InstanceManagerInterface $instanceManager,
    ): void {
        $this->instanceManager = $instanceManager;
    }

    abstract protected function getMandatoryDirectives(mixed $entryValue = null): array;

    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        foreach ($this->getEntries() as $entry) {
            $directiveResolverClass = $entry[0];
            $entryValue = $entry[1] ?? null; // this might be any value (string, array, etc) or, if not defined, null
            /**
             * Because the entries can be stored in DB, we run the risk that the
             * configured DirectiveResolver class will not exist anymore.
             * So check that the instance exists, and if it doesn't, then
             * skip processing the entry
             */
            if (!$this->instanceManager->hasInstance($directiveResolverClass)) {
                continue;
            }
            /**
             * Just to be on the safe side, also validate the instance is a directive
             */
            $directiveResolver = $this->instanceManager->getInstance($directiveResolverClass);
            if (!($directiveResolver instanceof DirectiveResolverInterface)) {
                continue;
            }
            /** @var DirectiveResolverInterface $directiveResolver */
            $directiveName = $directiveResolver->getDirectiveName();
            $mandatoryDirectivesForDirectives[$directiveName] = $this->getMandatoryDirectives($entryValue);
        }
        return $mandatoryDirectivesForDirectives;
    }
}
