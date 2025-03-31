<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\TagMetaMutations\Module;
use PoPCMSSchema\TagMetaMutations\ModuleConfiguration;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\AddTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\DeleteTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableAddTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableDeleteTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableSetTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableUpdateTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\SetTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\MutationResolvers\UpdateTagTermMetaMutationResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType\TagTermAddMetaInputObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType\TagTermDeleteMetaInputObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType\TagTermSetMetaInputObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\TypeResolvers\InputObjectType\TagTermUpdateMetaInputObjectTypeResolver;
use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractTagObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?TagTermAddMetaInputObjectTypeResolver $tagTermAddMetaInputObjectTypeResolver = null;
    private ?TagTermDeleteMetaInputObjectTypeResolver $tagTermDeleteMetaInputObjectTypeResolver = null;
    private ?TagTermSetMetaInputObjectTypeResolver $tagTermSetMetaInputObjectTypeResolver = null;
    private ?TagTermUpdateMetaInputObjectTypeResolver $tagTermUpdateMetaInputObjectTypeResolver = null;
    private ?AddTagTermMetaMutationResolver $addTagTermMetaMutationResolver = null;
    private ?DeleteTagTermMetaMutationResolver $deleteTagTermMetaMutationResolver = null;
    private ?SetTagTermMetaMutationResolver $setTagTermMetaMutationResolver = null;
    private ?UpdateTagTermMetaMutationResolver $updateTagTermMetaMutationResolver = null;
    private ?PayloadableDeleteTagTermMetaMutationResolver $payloadableDeleteTagTermMetaMutationResolver = null;
    private ?PayloadableSetTagTermMetaMutationResolver $payloadableSetTagTermMetaMutationResolver = null;
    private ?PayloadableUpdateTagTermMetaMutationResolver $payloadableUpdateTagTermMetaMutationResolver = null;
    private ?PayloadableAddTagTermMetaMutationResolver $payloadableAddTagTermMetaMutationResolver = null;

    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        if ($this->userLoggedInCheckpoint === null) {
            /** @var UserLoggedInCheckpoint */
            $userLoggedInCheckpoint = $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
            $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
        }
        return $this->userLoggedInCheckpoint;
    }
    final protected function getTagTermAddMetaInputObjectTypeResolver(): TagTermAddMetaInputObjectTypeResolver
    {
        if ($this->tagTermAddMetaInputObjectTypeResolver === null) {
            /** @var TagTermAddMetaInputObjectTypeResolver */
            $tagTermAddMetaInputObjectTypeResolver = $this->instanceManager->getInstance(TagTermAddMetaInputObjectTypeResolver::class);
            $this->tagTermAddMetaInputObjectTypeResolver = $tagTermAddMetaInputObjectTypeResolver;
        }
        return $this->tagTermAddMetaInputObjectTypeResolver;
    }
    final protected function getTagTermDeleteMetaInputObjectTypeResolver(): TagTermDeleteMetaInputObjectTypeResolver
    {
        if ($this->tagTermDeleteMetaInputObjectTypeResolver === null) {
            /** @var TagTermDeleteMetaInputObjectTypeResolver */
            $tagTermDeleteMetaInputObjectTypeResolver = $this->instanceManager->getInstance(TagTermDeleteMetaInputObjectTypeResolver::class);
            $this->tagTermDeleteMetaInputObjectTypeResolver = $tagTermDeleteMetaInputObjectTypeResolver;
        }
        return $this->tagTermDeleteMetaInputObjectTypeResolver;
    }
    final protected function getTagTermSetMetaInputObjectTypeResolver(): TagTermSetMetaInputObjectTypeResolver
    {
        if ($this->tagTermSetMetaInputObjectTypeResolver === null) {
            /** @var TagTermSetMetaInputObjectTypeResolver */
            $tagTermSetMetaInputObjectTypeResolver = $this->instanceManager->getInstance(TagTermSetMetaInputObjectTypeResolver::class);
            $this->tagTermSetMetaInputObjectTypeResolver = $tagTermSetMetaInputObjectTypeResolver;
        }
        return $this->tagTermSetMetaInputObjectTypeResolver;
    }
    final protected function getTagTermUpdateMetaInputObjectTypeResolver(): TagTermUpdateMetaInputObjectTypeResolver
    {
        if ($this->tagTermUpdateMetaInputObjectTypeResolver === null) {
            /** @var TagTermUpdateMetaInputObjectTypeResolver */
            $tagTermUpdateMetaInputObjectTypeResolver = $this->instanceManager->getInstance(TagTermUpdateMetaInputObjectTypeResolver::class);
            $this->tagTermUpdateMetaInputObjectTypeResolver = $tagTermUpdateMetaInputObjectTypeResolver;
        }
        return $this->tagTermUpdateMetaInputObjectTypeResolver;
    }
    final protected function getAddTagTermMetaMutationResolver(): AddTagTermMetaMutationResolver
    {
        if ($this->addTagTermMetaMutationResolver === null) {
            /** @var AddTagTermMetaMutationResolver */
            $addTagTermMetaMutationResolver = $this->instanceManager->getInstance(AddTagTermMetaMutationResolver::class);
            $this->addTagTermMetaMutationResolver = $addTagTermMetaMutationResolver;
        }
        return $this->addTagTermMetaMutationResolver;
    }
    final protected function getDeleteTagTermMetaMutationResolver(): DeleteTagTermMetaMutationResolver
    {
        if ($this->deleteTagTermMetaMutationResolver === null) {
            /** @var DeleteTagTermMetaMutationResolver */
            $deleteTagTermMetaMutationResolver = $this->instanceManager->getInstance(DeleteTagTermMetaMutationResolver::class);
            $this->deleteTagTermMetaMutationResolver = $deleteTagTermMetaMutationResolver;
        }
        return $this->deleteTagTermMetaMutationResolver;
    }
    final protected function getSetTagTermMetaMutationResolver(): SetTagTermMetaMutationResolver
    {
        if ($this->setTagTermMetaMutationResolver === null) {
            /** @var SetTagTermMetaMutationResolver */
            $setTagTermMetaMutationResolver = $this->instanceManager->getInstance(SetTagTermMetaMutationResolver::class);
            $this->setTagTermMetaMutationResolver = $setTagTermMetaMutationResolver;
        }
        return $this->setTagTermMetaMutationResolver;
    }
    final protected function getUpdateTagTermMetaMutationResolver(): UpdateTagTermMetaMutationResolver
    {
        if ($this->updateTagTermMetaMutationResolver === null) {
            /** @var UpdateTagTermMetaMutationResolver */
            $updateTagTermMetaMutationResolver = $this->instanceManager->getInstance(UpdateTagTermMetaMutationResolver::class);
            $this->updateTagTermMetaMutationResolver = $updateTagTermMetaMutationResolver;
        }
        return $this->updateTagTermMetaMutationResolver;
    }
    final protected function getPayloadableDeleteTagTermMetaMutationResolver(): PayloadableDeleteTagTermMetaMutationResolver
    {
        if ($this->payloadableDeleteTagTermMetaMutationResolver === null) {
            /** @var PayloadableDeleteTagTermMetaMutationResolver */
            $payloadableDeleteTagTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteTagTermMetaMutationResolver::class);
            $this->payloadableDeleteTagTermMetaMutationResolver = $payloadableDeleteTagTermMetaMutationResolver;
        }
        return $this->payloadableDeleteTagTermMetaMutationResolver;
    }
    final protected function getPayloadableSetTagTermMetaMutationResolver(): PayloadableSetTagTermMetaMutationResolver
    {
        if ($this->payloadableSetTagTermMetaMutationResolver === null) {
            /** @var PayloadableSetTagTermMetaMutationResolver */
            $payloadableSetTagTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableSetTagTermMetaMutationResolver::class);
            $this->payloadableSetTagTermMetaMutationResolver = $payloadableSetTagTermMetaMutationResolver;
        }
        return $this->payloadableSetTagTermMetaMutationResolver;
    }
    final protected function getPayloadableUpdateTagTermMetaMutationResolver(): PayloadableUpdateTagTermMetaMutationResolver
    {
        if ($this->payloadableUpdateTagTermMetaMutationResolver === null) {
            /** @var PayloadableUpdateTagTermMetaMutationResolver */
            $payloadableUpdateTagTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateTagTermMetaMutationResolver::class);
            $this->payloadableUpdateTagTermMetaMutationResolver = $payloadableUpdateTagTermMetaMutationResolver;
        }
        return $this->payloadableUpdateTagTermMetaMutationResolver;
    }
    final protected function getPayloadableAddTagTermMetaMutationResolver(): PayloadableAddTagTermMetaMutationResolver
    {
        if ($this->payloadableAddTagTermMetaMutationResolver === null) {
            /** @var PayloadableAddTagTermMetaMutationResolver */
            $payloadableAddTagTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableAddTagTermMetaMutationResolver::class);
            $this->payloadableAddTagTermMetaMutationResolver = $payloadableAddTagTermMetaMutationResolver;
        }
        return $this->payloadableAddTagTermMetaMutationResolver;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'addMeta',
            'deleteMeta',
            'setMeta',
            'updateMeta',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'addMeta' => $this->__('Add a tag term meta entry', 'tagmeta-mutations'),
            'deleteMeta' => $this->__('Delete a tag term meta entry', 'tagmeta-mutations'),
            'setMeta' => $this->__('Set meta entries to a a tag term', 'tagmeta-mutations'),
            'updateMeta' => $this->__('Update a tag term meta entry', 'tagmeta-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableTagMetaMutations = $moduleConfiguration->usePayloadableTagMetaMutations();
        if (!$usePayloadableTagMetaMutations) {
            return match ($fieldName) {
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => SchemaTypeModifiers::NONE,
                default
                    => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta',
            'deleteMeta',
            'setMeta',
            'updateMeta'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'addMeta' => [
                'input' => $this->getTagTermAddMetaInputObjectTypeResolver(),
            ],
            'deleteMeta' => [
                'input' => $this->getTagTermDeleteMetaInputObjectTypeResolver(),
            ],
            'setMeta' => [
                'input' => $this->getTagTermSetMetaInputObjectTypeResolver(),
            ],
            'updateMeta' => [
                'input' => $this->getTagTermUpdateMetaInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['addMeta' => 'input'],
            ['deleteMeta' => 'input'],
            ['setMeta' => 'input'],
            ['updateMeta' => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in the field argument in the query
     */
    public function validateMutationOnObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
            case 'updateMeta':
                return true;
        }
        return parent::validateMutationOnObject($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string,mixed> $fieldArgsForMutationForObject
     * @return array<string,mixed>
     */
    public function prepareFieldArgsForMutationForObject(
        array $fieldArgsForMutationForObject,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        object $object,
    ): array {
        $fieldArgsForMutationForObject = parent::prepareFieldArgsForMutationForObject(
            $fieldArgsForMutationForObject,
            $objectTypeResolver,
            $field,
            $object,
        );
        $tag = $object;
        switch ($field->getName()) {
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
            case 'updateMeta':
                $fieldArgsForMutationForObject['input']->{MutationInputProperties::ID} = $objectTypeResolver->getID($tag);
                break;
        }
        return $fieldArgsForMutationForObject;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableTagMetaMutations = $moduleConfiguration->usePayloadableTagMetaMutations();
        return match ($fieldName) {
            'addMeta' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableAddTagTermMetaMutationResolver()
                : $this->getAddTagTermMetaMutationResolver(),
            'updateMeta' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableUpdateTagTermMetaMutationResolver()
                : $this->getUpdateTagTermMetaMutationResolver(),
            'deleteMeta' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableDeleteTagTermMetaMutationResolver()
                : $this->getDeleteTagTermMetaMutationResolver(),
            'setMeta' => $usePayloadableTagMetaMutations
                ? $this->getPayloadableSetTagTermMetaMutationResolver()
                : $this->getSetTagTermMetaMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return CheckpointInterface[]
     */
    public function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): array {
        $validationCheckpoints = parent::getValidationCheckpoints(
            $objectTypeResolver,
            $fieldDataAccessor,
            $object,
        );

        /**
         * For Payloadable: The "User Logged-in" checkpoint validation is not added,
         * instead this validation is executed inside the mutation, so the error
         * shows up in the Payload
         *
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableTagMetaMutations = $moduleConfiguration->usePayloadableTagMetaMutations();
        if ($usePayloadableTagMetaMutations) {
            return $validationCheckpoints;
        }

        switch ($fieldDataAccessor->getFieldName()) {
            case 'addMeta':
            case 'deleteMeta':
            case 'setMeta':
            case 'updateMeta':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }
}
