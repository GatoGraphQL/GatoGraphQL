<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\FilterCommentsByCommentAuthorInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\FilterCommentsByCustomPostAuthorInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentsFilterInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class InputObjectTypeHookSet extends AbstractHookSet
{
    private ?FilterCommentsByCommentAuthorInputObjectTypeResolver $filterCommentsByCommentAuthorInputObjectTypeResolver = null;
    private ?FilterCommentsByCustomPostAuthorInputObjectTypeResolver $filterCommentsByCustomPostAuthorInputObjectTypeResolver = null;

    final protected function getFilterCommentsByCommentAuthorInputObjectTypeResolver(): FilterCommentsByCommentAuthorInputObjectTypeResolver
    {
        if ($this->filterCommentsByCommentAuthorInputObjectTypeResolver === null) {
            /** @var FilterCommentsByCommentAuthorInputObjectTypeResolver */
            $filterCommentsByCommentAuthorInputObjectTypeResolver = $this->instanceManager->getInstance(FilterCommentsByCommentAuthorInputObjectTypeResolver::class);
            $this->filterCommentsByCommentAuthorInputObjectTypeResolver = $filterCommentsByCommentAuthorInputObjectTypeResolver;
        }
        return $this->filterCommentsByCommentAuthorInputObjectTypeResolver;
    }
    final protected function getFilterCommentsByCustomPostAuthorInputObjectTypeResolver(): FilterCommentsByCustomPostAuthorInputObjectTypeResolver
    {
        if ($this->filterCommentsByCustomPostAuthorInputObjectTypeResolver === null) {
            /** @var FilterCommentsByCustomPostAuthorInputObjectTypeResolver */
            $filterCommentsByCustomPostAuthorInputObjectTypeResolver = $this->instanceManager->getInstance(FilterCommentsByCustomPostAuthorInputObjectTypeResolver::class);
            $this->filterCommentsByCustomPostAuthorInputObjectTypeResolver = $filterCommentsByCustomPostAuthorInputObjectTypeResolver;
        }
        return $this->filterCommentsByCustomPostAuthorInputObjectTypeResolver;
    }

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
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'author' => $this->getFilterCommentsByCommentAuthorInputObjectTypeResolver(),
                'customPostAuthor' => $this->getFilterCommentsByCustomPostAuthorInputObjectTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'author' => $this->__('Filter comments by author', 'comments'),
            'customPostAuthor' => $this->__('Filter comments added to custom posts from the given authors', 'comments'),
            default => $inputFieldDescription,
        };
    }
}
