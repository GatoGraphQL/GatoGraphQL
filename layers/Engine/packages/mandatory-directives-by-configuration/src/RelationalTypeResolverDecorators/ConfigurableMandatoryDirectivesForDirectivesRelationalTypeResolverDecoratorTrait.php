<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForDirectivesTrait;
use PoP\Root\Instances\InstanceManagerInterface;

trait ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait
{
    use ConfigurableMandatoryDirectivesForDirectivesTrait;

    abstract protected function getInstanceManager(): InstanceManagerInterface;

    /**
     * @return Directive[]
     */
    abstract protected function getMandatoryDirectives(mixed $entryValue = null): array;

    /**
     * @return array<string,Directive[]>
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $instanceManager = $this->getInstanceManager();

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
            if (!$instanceManager->hasInstance($directiveResolverClass)) {
                continue;
            }
            /**
             * Just to be on the safe side, also validate the instance is a directive
             */
            $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
            if (!($directiveResolver instanceof FieldDirectiveResolverInterface)) {
                continue;
            }
            /** @var FieldDirectiveResolverInterface $directiveResolver */
            $directiveName = $directiveResolver->getDirectiveName();
            $mandatoryDirectivesForDirectives[$directiveName] = $this->getMandatoryDirectives($entryValue);
        }
        return $mandatoryDirectivesForDirectives;
    }
}
