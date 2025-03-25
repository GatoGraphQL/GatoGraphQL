<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\SchemaHooks;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\CategoryByOneofInputObjectTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\CreateCustomPostInputObjectTypeResolverInterface;
use PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType\UpdateCustomPostInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractCategoryMutationResolverHookSet extends AbstractHookSet
{
    private ?CategoryByOneofInputObjectTypeResolver $categoryByOneofInputObjectTypeResolver = null;

    final protected function getCategoryByOneofInputObjectTypeResolver(): CategoryByOneofInputObjectTypeResolver
    {
        if ($this->categoryByOneofInputObjectTypeResolver === null) {
            /** @var CategoryByOneofInputObjectTypeResolver */
            $categoryByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CategoryByOneofInputObjectTypeResolver::class);
            $this->categoryByOneofInputObjectTypeResolver = $categoryByOneofInputObjectTypeResolver;
        }
        return $this->categoryByOneofInputObjectTypeResolver;
    }

    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $this->maybeAddInputFieldNameTypeResolvers(...),
            10,
            2
        );
        App::addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            $this->maybeAddInputFieldDescription(...),
            10,
            3
        );
    }

    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     */
    public function maybeAddInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        // Only for the specific combinations of Type and fieldName
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        $inputFieldNameTypeResolvers[MutationInputProperties::CATEGORY_BY] = $this->getCategoryByOneofInputObjectTypeResolver();
        return $inputFieldNameTypeResolvers;
    }

    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateCustomPostInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateCustomPostInputObjectTypeResolverInterface;
    }

    public function maybeAddInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?string {
        // Only for the newly added inputFieldName
        if ($inputFieldName !== MutationInputProperties::CATEGORY_BY || !$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return sprintf(
            $this->__('The categories to set, of type \'%s\'', 'custompost-categorymeta-mutations'),
            $this->getCategoryTypeResolver()->getMaybeNamespacedTypeName()
        );
    }

    abstract protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
}
