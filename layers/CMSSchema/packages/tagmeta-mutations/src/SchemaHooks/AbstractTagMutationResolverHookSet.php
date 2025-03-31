<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\SchemaHooks;

use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\CreateTagTermInputObjectTypeResolverInterface;
use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\UpdateTagTermInputObjectTypeResolverInterface;
use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\NullableListValueJSONObjectScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractTagMutationResolverHookSet extends AbstractHookSet
{
    private ?NullableListValueJSONObjectScalarTypeResolver $nullableListValueJSONObjectScalarTypeResolver = null;

    final protected function getNullableListValueJSONObjectScalarTypeResolver(): NullableListValueJSONObjectScalarTypeResolver
    {
        if ($this->nullableListValueJSONObjectScalarTypeResolver === null) {
            /** @var NullableListValueJSONObjectScalarTypeResolver */
            $nullableListValueJSONObjectScalarTypeResolver = $this->instanceManager->getInstance(NullableListValueJSONObjectScalarTypeResolver::class);
            $this->nullableListValueJSONObjectScalarTypeResolver = $nullableListValueJSONObjectScalarTypeResolver;
        }
        return $this->nullableListValueJSONObjectScalarTypeResolver;
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
        $inputFieldNameTypeResolvers[MutationInputProperties::META] = $this->getNullableListValueJSONObjectScalarTypeResolver();
        return $inputFieldNameTypeResolvers;
    }

    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateTagTermInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateTagTermInputObjectTypeResolverInterface;
    }

    public function maybeAddInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?string {
        // Only for the newly added inputFieldName
        if ($inputFieldName !== MutationInputProperties::META || !$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return $this->__('The meta to set', 'custompost-tagmeta-mutations');
    }

    abstract protected function getTagTypeResolver(): TagObjectTypeResolverInterface;
}
