<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\SchemaHooks;

use PoPCMSSchema\Categories\TypeResolvers\InputObjectType\FilterCustomPostsByCategoriesInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractAddCategoryFilterInputObjectTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $this->getInputFieldNameTypeResolvers(...),
            10,
            2
        );
        App::addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            $this->getInputFieldDescription(...),
            10,
            3
        );
    }

    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!(is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), true))) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'categories' => $this->getFilterCustomPostsByCategoriesInputObjectTypeResolver(),
            ]
        );
    }

    abstract protected function getInputObjectTypeResolverClass(): string;

    abstract protected function getFilterCustomPostsByCategoriesInputObjectTypeResolver(): FilterCustomPostsByCategoriesInputObjectTypeResolverInterface;

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!(is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), true))) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'categories' => $this->__('Filter by categories', 'categories'),
            default => $inputFieldDescription,
        };
    }
}
