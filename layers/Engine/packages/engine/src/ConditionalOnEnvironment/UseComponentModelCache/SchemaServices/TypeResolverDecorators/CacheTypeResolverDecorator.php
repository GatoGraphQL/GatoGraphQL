<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\DirectiveResolvers\LoadCacheDirectiveResolver;
use PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\DirectiveResolvers\SaveCacheDirectiveResolver;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;

class CacheTypeResolverDecorator extends AbstractTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return array(
            AbstractTypeResolver::class,
        );
    }

    /**
     * Directives @loadCache and @saveCache (called @cache) always go together
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $loadCacheDirective = $fieldQueryInterpreter->getDirective(
            LoadCacheDirectiveResolver::getDirectiveName()
        );
        return [
            SaveCacheDirectiveResolver::getDirectiveName() => [
                $loadCacheDirective,
            ],
        ];
    }
}
