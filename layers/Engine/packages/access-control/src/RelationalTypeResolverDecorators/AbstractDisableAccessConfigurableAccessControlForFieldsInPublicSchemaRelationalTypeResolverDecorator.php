<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessDirectiveResolver;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    protected ?DisableAccessDirectiveResolver $disableAccessDirectiveResolver = null;

    #[Required]
    final public function autowireAbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator(
        DisableAccessDirectiveResolver $disableAccessDirectiveResolver,
    ): void {
        $this->disableAccessDirectiveResolver = $disableAccessDirectiveResolver;
    }

    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $disableAccessDirective = $this->getFieldQueryInterpreter()->getDirective(
            $this->getDisableAccessDirectiveResolver()->getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}
