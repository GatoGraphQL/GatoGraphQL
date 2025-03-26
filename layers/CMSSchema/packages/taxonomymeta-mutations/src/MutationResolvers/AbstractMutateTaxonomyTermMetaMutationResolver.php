<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutations\Constants\TaxonomyMetaCRUDHookNames;
use PoPCMSSchema\TaxonomyMetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\TaxonomyMetaMutations\Exception\TaxonomyTermMetaCRUDMutationException;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;

abstract class AbstractMutateTaxonomyTermMetaMutationResolver extends AbstractMutationResolver implements TaxonomyTermMetaMutationResolverInterface
{
    use MutateTaxonomyTermMetaMutationResolverTrait;

    private ?TaxonomyMetaTypeMutationAPIInterface $taxonomyTypeMutationAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final protected function getTaxonomyMetaTypeMutationAPI(): TaxonomyMetaTypeMutationAPIInterface
    {
        if ($this->taxonomyTypeMutationAPI === null) {
            /** @var TaxonomyMetaTypeMutationAPIInterface */
            $taxonomyTypeMutationAPI = $this->instanceManager->getInstance(TaxonomyMetaTypeMutationAPIInterface::class);
            $this->taxonomyTypeMutationAPI = $taxonomyTypeMutationAPI;
        }
        return $this->taxonomyTypeMutationAPI;
    }
    final protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        if ($this->taxonomyTermTypeAPI === null) {
            /** @var TaxonomyTermTypeAPIInterface */
            $taxonomyTermTypeAPI = $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
            $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
        }
        return $this->taxonomyTermTypeAPI;
    }

    protected function validateSetMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            TaxonomyMetaCRUDHookNames::VALIDATE_SET_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $this->validateCommonMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateAddMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            TaxonomyMetaCRUDHookNames::VALIDATE_ADD_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->validateCommonMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var bool */
        $single = $fieldDataAccessor->getValue(MutationInputProperties::SINGLE);
        if ($single) {
            $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
            /** @var string */
            $key = $fieldDataAccessor->getValue(MutationInputProperties::KEY);
            $this->validateSingleMetaEntryDoesNotExist(
                $taxonomyTermID,
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

        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);

        $this->validateTaxonomyTermByIDExists(
            $taxonomyTermID,
            null,
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

        $taxonomyName = $this->getTaxonomyName($fieldDataAccessor);

        $this->validateCanLoggedInUserEditTaxonomy(
            $taxonomyName,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * For the `create` mutation, the taxonomy input is mandatory.
     * For the `updated` and `delete` mutations, the taxonomy input is optional.
     * If not provided, take it from the mutated entity.
     */
    protected function getTaxonomyName(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string {
        /** @var string|int */
        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        /** @var string */
        return $this->getTaxonomyTermTypeAPI()->getTaxonomyTermTaxonomy($taxonomyTermID);
    }

    protected function validateUpdateMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            TaxonomyMetaCRUDHookNames::VALIDATE_UPDATE_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $this->validateCommonMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateDeleteMetaErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        App::doAction(
            TaxonomyMetaCRUDHookNames::VALIDATE_DELETE_META,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $this->validateCommonMetaErrors(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getSetMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = [
            'entries' => $fieldDataAccessor->getValue(MutationInputProperties::ENTRIES),
        ];

        $taxonomyData = App::applyFilters(TaxonomyMetaCRUDHookNames::GET_SET_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAddMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = [
            'key' => $fieldDataAccessor->getValue(MutationInputProperties::KEY),
            'value' => $fieldDataAccessor->getValue(MutationInputProperties::VALUE),
            'single' => $fieldDataAccessor->getValue(MutationInputProperties::SINGLE),
        ];

        $taxonomyData = App::applyFilters(TaxonomyMetaCRUDHookNames::GET_ADD_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUpdateMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = [
            'key' => $fieldDataAccessor->getValue(MutationInputProperties::KEY),
            'value' => $fieldDataAccessor->getValue(MutationInputProperties::VALUE),
        ];

        $taxonomyData = App::applyFilters(TaxonomyMetaCRUDHookNames::GET_UPDATE_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getDeleteMetaData(FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $taxonomyData = [
            'key' => $fieldDataAccessor->getValue(MutationInputProperties::KEY),
        ];

        $taxonomyData = App::applyFilters(TaxonomyMetaCRUDHookNames::GET_DELETE_META_DATA, $taxonomyData, $fieldDataAccessor);

        return $taxonomyData;
    }

    /**
     * @return string|int The ID of the updated entity
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function updateMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string|int */
        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $taxonomyName = $this->getTaxonomyName($fieldDataAccessor);
        $taxonomyData = $this->getUpdateMetaData($fieldDataAccessor);

        $taxonomyTermID = $this->executeUpdateTaxonomyTermMeta($taxonomyTermID, $taxonomyName, $taxonomyData);

        App::doAction(
            TaxonomyMetaCRUDHookNames::EXECUTE_UPDATE_META,
            $taxonomyTermID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        return $taxonomyTermID;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the updated taxonomy
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeUpdateTaxonomyTermMeta(string|int $taxonomyTermID, string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getTaxonomyMetaTypeMutationAPI()->updateTaxonomyTermMeta($taxonomyTermID, $taxonomyName, $taxonomyData);
    }

    /**
     * @return string|int The ID of the created entity
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function addMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int {
        /** @var string */
        $taxonomyName = $this->getTaxonomyName($fieldDataAccessor);
        $taxonomyData = $this->getAddMetaData($fieldDataAccessor);
        $taxonomyTermID = $this->executeAddTaxonomyTermMeta($taxonomyName, $taxonomyData);

        App::doAction(TaxonomyMetaCRUDHookNames::EXECUTE_ADD_META, $taxonomyTermID, $fieldDataAccessor);

        return $taxonomyTermID;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the created taxonomy
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeAddTaxonomyTermMeta(string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getTaxonomyMetaTypeMutationAPI()->addTaxonomyTermMeta($taxonomyName, $taxonomyData);
    }

    /**
     * @return bool Was the deletion successful?
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function deleteMeta(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): bool {
        /** @var string|int */
        $taxonomyTermID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $taxonomyName = $this->getTaxonomyName($fieldDataAccessor);
        $taxonomyData = $this->getUpdateMetaData($fieldDataAccessor);

        $result = $this->executeDeleteTaxonomyTermMeta($taxonomyTermID, $taxonomyName, $taxonomyData);
        if ($result === false) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getTaxonomyTermDoesNotExistError($taxonomyName, $taxonomyTermID),
                    $fieldDataAccessor->getField(),
                )
            );
        }

        return $result;
    }

    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws TaxonomyTermMetaCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeDeleteTaxonomyTermMeta(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        return $this->getTaxonomyMetaTypeMutationAPI()->deleteTaxonomyTermMeta($taxonomyTermID, $taxonomyName);
    }
}
