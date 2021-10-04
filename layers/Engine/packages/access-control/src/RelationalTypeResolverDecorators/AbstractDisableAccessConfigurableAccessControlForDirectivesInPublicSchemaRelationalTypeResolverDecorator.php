<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessForDirectivesDirectiveResolver;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    protected DisableAccessForDirectivesDirectiveResolver $disableAccessForDirectivesDirectiveResolver;

    #[Required]
    final public function autowireAbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator(
        DisableAccessForDirectivesDirectiveResolver $disableAccessForDirectivesDirectiveResolver,
    ): void {
        $this->disableAccessForDirectivesDirectiveResolver = $disableAccessForDirectivesDirectiveResolver;
    }

    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $disableAccessDirective = $this->fieldQueryInterpreter->getDirective(
            $this->disableAccessForDirectivesDirectiveResolver->getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}
