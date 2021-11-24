<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\Hooks\AbstractHookSet;

abstract class AbstractRemoveAuthorInputFieldsFromCustomPostInputObjectTypeHookSet extends AbstractHookSet
{
    private ?AddAuthorInputFieldsToCustomPostInputObjectTypeHookSet $addAuthorInputFieldsToCustomPostInputObjectTypeHookSet = null;

    final public function setAddAuthorInputFieldsToCustomPostInputObjectTypeHookSet(AddAuthorInputFieldsToCustomPostInputObjectTypeHookSet $addAuthorInputFieldsToCustomPostInputObjectTypeHookSet): void
    {
        $this->addAuthorInputFieldsToCustomPostInputObjectTypeHookSet = $addAuthorInputFieldsToCustomPostInputObjectTypeHookSet;
    }
    final protected function getAddAuthorInputFieldsToCustomPostInputObjectTypeHookSet(): AddAuthorInputFieldsToCustomPostInputObjectTypeHookSet
    {
        return $this->addAuthorInputFieldsToCustomPostInputObjectTypeHookSet ??= $this->instanceManager->getInstance(AddAuthorInputFieldsToCustomPostInputObjectTypeHookSet::class);
    }
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            [$this, 'getInputFieldNameTypeResolvers'],
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
     * @param array<string, InputTypeResolverInterface> $inputFieldNameTypeResolvers
     */
    public function getInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!$this->removeAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        $authorInputFieldNames = array_keys($this->getAddAuthorInputFieldsToCustomPostInputObjectTypeHookSet()->getAuthorInputFieldNameTypeResolvers());
        foreach ($authorInputFieldNames as $authorInputFieldName) {
            unset($inputFieldNameTypeResolvers[$authorInputFieldName]);
        }
        return $inputFieldNameTypeResolvers;
    }
}
