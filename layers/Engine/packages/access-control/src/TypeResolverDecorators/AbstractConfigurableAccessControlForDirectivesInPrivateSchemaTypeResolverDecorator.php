<?php

declare(strict_types=1);

namespace PoP\AccessControl\TypeResolverDecorators;

abstract class AbstractConfigurableAccessControlForDirectivesInPrivateSchemaTypeResolverDecorator extends AbstractPrivateSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForDirectivesTypeResolverDecoratorTrait;
}
