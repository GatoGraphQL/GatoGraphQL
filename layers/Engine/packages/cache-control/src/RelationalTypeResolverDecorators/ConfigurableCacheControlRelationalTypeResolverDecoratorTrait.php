<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait ConfigurableCacheControlRelationalTypeResolverDecoratorTrait
{
    protected ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    protected ?CacheControlDirectiveResolver $cacheControlDirectiveResolver = null;

    #[Required]
    public function autowireConfigurableCacheControlRelationalTypeResolverDecoratorTrait(
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        CacheControlDirectiveResolver $cacheControlDirectiveResolver,
    ): void {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
        $this->cacheControlDirectiveResolver = $cacheControlDirectiveResolver;
    }

    /**
     * By default, only the admin can see the roles from the users
     */
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $maxAge = $entryValue;
        $cacheControlDirective = $this->getFieldQueryInterpreter()->getDirective(
            $this->getCacheControlDirectiveResolver()->getDirectiveName(),
            [
                'maxAge' => $maxAge,
            ]
        );
        return [
            $cacheControlDirective,
        ];
    }
}
