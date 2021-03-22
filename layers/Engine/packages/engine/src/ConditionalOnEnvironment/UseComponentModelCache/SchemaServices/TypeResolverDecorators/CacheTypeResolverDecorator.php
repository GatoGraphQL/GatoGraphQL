<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\TypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\DirectiveResolvers\LoadCacheDirectiveResolver;
use PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\DirectiveResolvers\SaveCacheDirectiveResolver;

class CacheTypeResolverDecorator extends AbstractTypeResolverDecorator
{
    public function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    /**
     * Directives @loadCache and @saveCache (called @cache) always go together
     */
    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $loadCacheDirectiveResolver = $instanceManager->getInstance(LoadCacheDirectiveResolver::class);
        /** @var DirectiveResolverInterface */
        $saveCacheDirectiveResolver = $instanceManager->getInstance(SaveCacheDirectiveResolver::class);
        $loadCacheDirective = $fieldQueryInterpreter->getDirective(
            $loadCacheDirectiveResolver->getDirectiveName()
        );
        return [
            $saveCacheDirectiveResolver->getDirectiveName() => [
                $loadCacheDirective,
            ],
        ];
    }
}
