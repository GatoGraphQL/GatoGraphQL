<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\SchemaHooks;

use PoPCMSSchema\CustomPostTagMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\TagsByOneofInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    private ?TagsByOneofInputObjectTypeResolver $tagsByOneofInputObjectTypeResolver = null;

    final protected function getTagsByOneofInputObjectTypeResolver(): TagsByOneofInputObjectTypeResolver
    {
        if ($this->tagsByOneofInputObjectTypeResolver === null) {
            /** @var TagsByOneofInputObjectTypeResolver */
            $tagsByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(TagsByOneofInputObjectTypeResolver::class);
            $this->tagsByOneofInputObjectTypeResolver = $tagsByOneofInputObjectTypeResolver;
        }
        return $this->tagsByOneofInputObjectTypeResolver;
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
        $inputFieldNameTypeResolvers[MutationInputProperties::TAGS_BY] = $this->getTagsByOneofInputObjectTypeResolver();
        return $inputFieldNameTypeResolvers;
    }

    abstract protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool;

    public function maybeAddInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?string {
        // Only for the newly added inputFieldName
        if ($inputFieldName !== MutationInputProperties::TAGS_BY || !$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return sprintf(
            $this->__('The tags to set, of type \'%s\'', 'custompost-tag-mutations'),
            $this->getTagTypeResolver()->getMaybeNamespacedTypeName()
        );
    }

    abstract protected function getTagTypeResolver(): TagObjectTypeResolverInterface;
}
