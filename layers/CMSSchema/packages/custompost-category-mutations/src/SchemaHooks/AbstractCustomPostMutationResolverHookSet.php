<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\SchemaHooks;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\CategoriesByOneofInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CreateCustomPostInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateCustomPostInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    private ?CategoriesByOneofInputObjectTypeResolver $categoriesByOneofInputObjectTypeResolver = null;

    final public function setCategoriesByOneofInputObjectTypeResolver(CategoriesByOneofInputObjectTypeResolver $categoriesByOneofInputObjectTypeResolver): void
    {
        $this->categoriesByOneofInputObjectTypeResolver = $categoriesByOneofInputObjectTypeResolver;
    }
    final protected function getCategoriesByOneofInputObjectTypeResolver(): CategoriesByOneofInputObjectTypeResolver
    {
        if ($this->categoriesByOneofInputObjectTypeResolver === null) {
            /** @var CategoriesByOneofInputObjectTypeResolver */
            $categoriesByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CategoriesByOneofInputObjectTypeResolver::class);
            $this->categoriesByOneofInputObjectTypeResolver = $categoriesByOneofInputObjectTypeResolver;
        }
        return $this->categoriesByOneofInputObjectTypeResolver;
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
        $inputFieldNameTypeResolvers[MutationInputProperties::CATEGORIES_BY] = $this->getCategoriesByOneofInputObjectTypeResolver();
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
        if ($inputFieldName !== MutationInputProperties::CATEGORIES_BY || !$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return sprintf(
            $this->__('The categories to set, of type \'%s\'', 'custompost-category-mutations'),
            $this->getCategoryTypeResolver()->getMaybeNamespacedTypeName()
        );
    }

    abstract protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
}
