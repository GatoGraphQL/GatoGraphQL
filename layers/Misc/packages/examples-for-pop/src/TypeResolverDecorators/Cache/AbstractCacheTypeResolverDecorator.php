<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\TypeResolverDecorators\Cache;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\DirectiveResolvers\SaveCacheDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;

/**
 * Add directive @cache to fields expensive to calculate
 */
abstract class AbstractCacheTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    /**
     * Time that the cache is valid
     * By default, it caches for 1 hour
     *
     * @return integer|null
     */
    protected function getTime(): ?int
    {
        // By default, cache it for 1 hour (3600 seconds)
        return 3600;
    }

    /**
     * Get the fields to cache. Function to override
     *
     * @return array
     */
    protected function getFieldNamesToCache(): array
    {
        return [];
    }

    /**
     * Get the directives to cache. Function to override
     *
     * @return array
     */
    protected function getDirectiveNamesToCache(): array
    {
        return [];
    }

    /**
     * Get the cache directive
     *
     * @return array
     */
    protected function getCacheDirective(): array
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        return $fieldQueryInterpreter->getDirective(
            SaveCacheDirectiveResolver::getDirectiveName(),
            [
                'time' => $this->getTime(),
            ]
        );
    }

    /**
     * Cache fields
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForFields(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForFields = [];
        if ($fieldNames = $this->getFieldNamesToCache()) {
            $cacheDirective = $this->getCacheDirective();
            foreach ($fieldNames as $fieldName) {
                $mandatoryDirectivesForFields[$fieldName] = [
                    $cacheDirective,
                ];
            }
        }
        return $mandatoryDirectivesForFields;
    }

    /**
     * Cache directives
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getSucceedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForDirectives = [];
        if ($directiveNames = $this->getDirectiveNamesToCache()) {
            $cacheDirective = $this->getCacheDirective();
            foreach ($directiveNames as $directiveName) {
                $mandatoryDirectivesForDirectives[$directiveName] = [
                    $cacheDirective,
                ];
            }
        }
        return $mandatoryDirectivesForDirectives;
    }
}
