<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\MutationResolvers;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MetaMutations\Exception\TermMetaCRUDMutationException;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use stdClass;

abstract class AbstractMutateTermMetaMutationResolver extends AbstractMutationResolver implements TermMetaMutationResolverInterface
{
    use ValidateUserLoggedInMutationResolverTrait;
    use MutateTermMetaMutationResolverTrait;

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
            $termID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
            /** @var string */
            $key = $fieldDataAccessor->getValue(MutationInputProperties::KEY);
            $this->validateSingleMetaEntryDoesNotExist(
                $termID,
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

        $termID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        $this->validateTermExists(
            $termID,
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

        $this->validateUserCanEditTerm(
            $termID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    abstract protected function validateTermExists(
        string|int $termID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;

    abstract protected function validateUserCanEditTerm(
        string|int $termID,
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
            $termID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
            $this->validateMetaEntryWithValueExists(
                $termID,
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

        $termID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $this->validateMetaEntryExists(
            $termID,
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
     * @throws TermMetaCRUDMutationException If there was an error (eg: some entity term creation validation failed)
     */
    protected function addMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $termID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $metaData = $this->getAddMetaData($fieldDataAccessor);
        $termMetaID = $this->executeAddTermMeta($termID, $metaData['key'], $metaData['value'], $metaData['single']);

        return $termID;
    }

    /**
     * @return string|int the ID of the created entity
     * @throws TermMetaCRUDMutationException If there was an error (eg: some entity term creation validation failed)
     */
    abstract protected function executeAddTermMeta(string|int $termID, string $key, mixed $value, bool $single): string|int;

    /**
     * @return string|int The ID of the entity term
     * @throws TermMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    protected function updateMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $termID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $metaData = $this->getUpdateMetaData($fieldDataAccessor);

        $termMetaIDOrTrue = $this->executeUpdateTermMeta($termID, $metaData['key'], $metaData['value'], $metaData['prevValue']);

        return $termID;
    }

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws TermMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    abstract protected function executeUpdateTermMeta(string|int $termID, string $key, mixed $value, mixed $prevValue = null): string|int|bool;

    /**
     * @return string|int The ID of the entity term
     * @throws TermMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    protected function deleteMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $termID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $metaData = $this->getUpdateMetaData($fieldDataAccessor);
        $this->executeDeleteTermMeta($termID, $metaData['key']);

        return $termID;
    }

    /**
     * @throws TermMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    abstract protected function executeDeleteTermMeta(string|int $termID, string $key): void;

    /**
     * @return string|int The ID of the entity term
     * @throws TermMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    protected function setMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $termID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $metaData = $this->getSetMetaData($fieldDataAccessor);
        $this->executeSetTermMeta($termID, $metaData['entries']);

        return $termID;
    }

    /**
     * @param array<string,mixed[]|null> $entries
     * @throws TermMetaCRUDMutationException If there was an error (eg: entity term does not exist)
     */
    abstract protected function executeSetTermMeta(string|int $termID, array $entries): void;
}
