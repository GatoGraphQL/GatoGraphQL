<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Translation\TranslationAPIInterface;
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

trait UserStateObjectTypeFieldResolverTrait
{
    abstract protected function getTranslationAPI(): TranslationAPIInterface;

    protected function getValidationCheckpointSets(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs
    ): array {
        $validationCheckpointSets = parent::getValidationCheckpointSets(
            $objectTypeResolver,
            $object,
            $fieldName,
            $fieldArgs,
        );
        $validationCheckpointSets[] = UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER;
        return $validationCheckpointSets;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    protected function getValidationCheckpointsErrorMessage(
        string $errorMessage,
        array $checkpointSet,
        FeedbackItemResolution $feedbackItemResolution,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs
    ): string {
        if ($checkpointSet !== UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER) {
            return $errorMessage;
        }
        return sprintf(
            $this->getTranslationAPI()->__('You must be logged in to access field \'%s\' for type \'%s\'', ''),
            $fieldName,
            $objectTypeResolver->getMaybeNamespacedTypeName()
        );
    }
}
