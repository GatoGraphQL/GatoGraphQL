<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\AccessControl\DirectiveResolvers\DisableAccessForDirectivesDirectiveResolver;

abstract class AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    protected DisableAccessForDirectivesDirectiveResolver $disableAccessForDirectivesDirectiveResolver;

    #[Required]
    public function autowireAbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator(
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
