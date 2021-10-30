<?php

declare(strict_types=1);

namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait ConfigurableCacheControlRelationalTypeResolverDecoratorTrait
{
    // private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?CacheControlDirectiveResolver $cacheControlDirectiveResolver = null;

    /**
     * Service to be provided by the class, not the trait,
     * to avoid overriding a final method
     */
    abstract protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface;
    // public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    // {
    //     $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    // }
    // protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    // {
    //     return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    // }
    public function setCacheControlDirectiveResolver(CacheControlDirectiveResolver $cacheControlDirectiveResolver): void
    {
        $this->cacheControlDirectiveResolver = $cacheControlDirectiveResolver;
    }
    protected function getCacheControlDirectiveResolver(): CacheControlDirectiveResolver
    {
        return $this->cacheControlDirectiveResolver ??= $this->instanceManager->getInstance(CacheControlDirectiveResolver::class);
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
