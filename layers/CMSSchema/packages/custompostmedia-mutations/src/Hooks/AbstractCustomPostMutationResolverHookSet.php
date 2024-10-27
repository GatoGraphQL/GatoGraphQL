<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\Hooks;

use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\FeaturedImageByOneofInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CreateCustomPostInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateCustomPostInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    private ?FeaturedImageByOneofInputObjectTypeResolver $featuredImageByOneofInputObjectTypeResolver = null;

    final protected function getFeaturedImageByOneofInputObjectTypeResolver(): FeaturedImageByOneofInputObjectTypeResolver
    {
        if ($this->featuredImageByOneofInputObjectTypeResolver === null) {
            /** @var FeaturedImageByOneofInputObjectTypeResolver */
            $featuredImageByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(FeaturedImageByOneofInputObjectTypeResolver::class);
            $this->featuredImageByOneofInputObjectTypeResolver = $featuredImageByOneofInputObjectTypeResolver;
        }
        return $this->featuredImageByOneofInputObjectTypeResolver;
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
        $inputFieldNameTypeResolvers[MutationInputProperties::FEATUREDIMAGE_BY] = $this->getFeaturedImageByOneofInputObjectTypeResolver();
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
        if ($inputFieldName !== MutationInputProperties::FEATUREDIMAGE_BY || !$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return $this->__('The image to set as featured', 'custompost-mutations');
    }
}
