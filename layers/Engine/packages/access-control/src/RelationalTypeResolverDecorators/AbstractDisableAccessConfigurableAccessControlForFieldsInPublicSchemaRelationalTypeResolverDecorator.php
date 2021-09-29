<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\AccessControl\DirectiveResolvers\DisableAccessDirectiveResolver;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    protected DisableAccessDirectiveResolver $disableAccessDirectiveResolver;

    #[Required]
    public function autowireAbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator(
        DisableAccessDirectiveResolver $disableAccessDirectiveResolver,
    ): void {
        $this->disableAccessDirectiveResolver = $disableAccessDirectiveResolver;
    }

    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $disableAccessDirective = $this->fieldQueryInterpreter->getDirective(
            $this->disableAccessDirectiveResolver->getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}
