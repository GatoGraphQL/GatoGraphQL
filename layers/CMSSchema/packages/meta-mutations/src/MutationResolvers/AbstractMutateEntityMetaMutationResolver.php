<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\Exception\EntityMetaCRUDMutationException;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use stdClass;

abstract class AbstractMutateEntityMetaMutationResolver extends AbstractMutationResolver implements EntityMetaMutationResolverInterface
{
    use ValidateUserLoggedInMutationResolverTrait;
    use MutateEntityMetaMutationResolverTrait;

    protected function validateSetMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->validateCommonMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var stdClass */
        $entries = $fieldDataAccessor->getValue(MutationInputProperties::ENTRIES);
        $keys = array_keys((array)$entries);
        $this->validateAreMetaKeysAllowed(
            $keys,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateAddMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->validateCommonMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $key = $fieldDataAccessor->getValue(MutationInputProperties::KEY);
        $this->validateAreMetaKeysAllowed(
            [$key],
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        /** @var bool */
        $single = $fieldDataAccessor->getValue(MutationInputProperties::SINGLE);
        if ($single) {
            $entityID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
            /** @var string */
            $key = $fieldDataAccessor->getValue(MutationInputProperties::KEY);
            $this->validateSingleMetaEntryDoesNotExist(
                $entityID,
                $key,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    protected function validateCommonMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $entityID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        $this->validateEntityExists(
            $entityID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $this->validateIsUserLoggedIn(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateUserCanEditEntity(
            $entityID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    abstract protected function validateEntityExists(
        string|int $entityID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;

    abstract protected function validateUserCanEditEntity(
        string|int $entityID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;

    protected function validateUpdateMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->validateCommonMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $key = $fieldDataAccessor->getValue(MutationInputProperties::KEY);
        $this->validateAreMetaKeysAllowed(
            [$key],
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $prevValue = $fieldDataAccessor->getValue(MutationInputProperties::PREV_VALUE);
        if (!empty($prevValue)) {
            $entityID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
            $this->validateMetaEntryWithValueExists(
                $entityID,
                $key,
                $prevValue,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    protected function validateDeleteMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->validateCommonMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $key = $fieldDataAccessor->getValue(MutationInputProperties::KEY);
        $this->validateAreMetaKeysAllowed(
            [$key],
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $entityID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $this->validateMetaEntryExists(
            $entityID,
            $key,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getSetMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return [
            'entries' => (array) $fieldDataAccessor->getValue(MutationInputProperties::ENTRIES),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAddMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return [
            'key' => $fieldDataAccessor->getValue(MutationInputProperties::KEY),
            'value' => $fieldDataAccessor->getValue(MutationInputProperties::VALUE),
            'single' => $fieldDataAccessor->getValue(MutationInputProperties::SINGLE) ?? false,
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return [
            'key' => $fieldDataAccessor->getValue(MutationInputProperties::KEY),
            'value' => $fieldDataAccessor->getValue(MutationInputProperties::VALUE),
            'prevValue' => $fieldDataAccessor->getValue(MutationInputProperties::PREV_VALUE),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getDeleteMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        return [
            'key' => $fieldDataAccessor->getValue(MutationInputProperties::KEY),
        ];
    }

    /**
     * @return string|int The ID of the entity term
     * @throws EntityMetaCRUDMutationException If there was an error (eg: some entity term creation validation failed)
     */
    protected function addMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $entityID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $metaData = $this->getAddMetaData($fieldDataAccessor);
        $termMetaID = $this->executeAddEntityMeta($entityID, $metaData['key'], $metaData['value'], $metaData['single']);

        return $entityID;
    }

    /**
     * @return string|int the ID of the created entity
     * @throws EntityMetaCRUDMutationException If there was an error (eg: some entity term creation validation failed)
     */
    abstract protected function executeAddEntityMeta(string|int $entityID, string $key, mixed $value, bool $single): string|int;

    /**
     * @return string|int The ID of the entity term
     * @throws EntityMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    protected function updateMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $entityID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $metaData = $this->getUpdateMetaData($fieldDataAccessor);

        $termMetaIDOrTrue = $this->executeUpdateEntityMeta($entityID, $metaData['key'], $metaData['value'], $metaData['prevValue']);

        return $entityID;
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws EntityMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    abstract protected function executeUpdateEntityMeta(string|int $entityID, string $key, mixed $value, mixed $prevValue = null): string|int|bool;

    /**
     * @return string|int The ID of the entity term
     * @throws EntityMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    protected function deleteMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $entityID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $metaData = $this->getUpdateMetaData($fieldDataAccessor);
        $this->executeDeleteEntityMeta($entityID, $metaData['key']);

        return $entityID;
    }

    /**
     * @throws EntityMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    abstract protected function executeDeleteEntityMeta(string|int $entityID, string $key): void;

    /**
     * @return string|int The ID of the entity term
     * @throws EntityMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    protected function setMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $entityID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $metaData = $this->getSetMetaData($fieldDataAccessor);
        $this->executeSetEntityMeta($entityID, $metaData['entries']);

        return $entityID;
    }

    /**
     * @param array<string,mixed[]|null> $entries
     * @throws EntityMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    abstract protected function executeSetEntityMeta(string|int $entityID, array $entries): void;
}
