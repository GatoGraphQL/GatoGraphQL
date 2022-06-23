<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessForDirectivesDirectiveResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

abstract class AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    protected ?Directive $disableAccessDirective = null;

    private ?DisableAccessForDirectivesDirectiveResolver $disableAccessForDirectivesDirectiveResolver = null;

    final public function setDisableAccessForDirectivesDirectiveResolver(DisableAccessForDirectivesDirectiveResolver $disableAccessForDirectivesDirectiveResolver): void
    {
        $this->disableAccessForDirectivesDirectiveResolver = $disableAccessForDirectivesDirectiveResolver;
    }
    final protected function getDisableAccessForDirectivesDirectiveResolver(): DisableAccessForDirectivesDirectiveResolver
    {
        return $this->disableAccessForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(DisableAccessForDirectivesDirectiveResolver::class);
    }

    /**
     * @return Directive[]
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        return [
            $this->getDisableAccessDirective(),
        ];
    }

    protected function getDisableAccessDirective(): Directive
    {
        if ($this->disableAccessDirective === null) {
            $this->disableAccessDirective = new Directive(
                $this->getDisableAccessForDirectivesDirectiveResolver()->getDirectiveName(),
                [],
                LocationHelper::getNonSpecificLocation()
            );
        }
        return $this->disableAccessDirective;
    }
}
