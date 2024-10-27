<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\SchemaHooks;

use PoPCMSSchema\Users\TypeResolvers\InputObjectType\FilterByAuthorInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractRemoveAuthorInputFieldsInputObjectTypeHookSet extends AbstractHookSet
{
    use AddOrRemoveAuthorInputFieldsInputObjectTypeHookSetTrait;

    private ?FilterByAuthorInputObjectTypeResolver $filterByAuthorInputObjectTypeResolver = null;

    final protected function getFilterByAuthorInputObjectTypeResolver(): FilterByAuthorInputObjectTypeResolver
    {
        if ($this->filterByAuthorInputObjectTypeResolver === null) {
            /** @var FilterByAuthorInputObjectTypeResolver */
            $filterByAuthorInputObjectTypeResolver = $this->instanceManager->getInstance(FilterByAuthorInputObjectTypeResolver::class);
            $this->filterByAuthorInputObjectTypeResolver = $filterByAuthorInputObjectTypeResolver;
        }
        return $this->filterByAuthorInputObjectTypeResolver;
    }

    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $this->getInputFieldNameTypeResolvers(...),
            100,
            2
        );
    }

    /**
     * Indicate if to remove the fields added by the SchemaHookSet
     */
    abstract protected function removeAuthorInputFields(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool;

    /**
     * Remove the fields added by the SchemaHookSet
     *
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!$this->removeAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        $authorInputFieldNames = array_keys($this->getAuthorInputFieldNameTypeResolvers());
        foreach ($authorInputFieldNames as $authorInputFieldName) {
            unset($inputFieldNameTypeResolvers[$authorInputFieldName]);
        }
        return $inputFieldNameTypeResolvers;
    }
}
