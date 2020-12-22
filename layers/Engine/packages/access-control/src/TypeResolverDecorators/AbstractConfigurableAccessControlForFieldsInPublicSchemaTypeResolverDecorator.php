<?php

declare(strict_types=1);

namespace PoP\AccessControl\TypeResolverDecorators;

abstract class AbstractConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;
}
