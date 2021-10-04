<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers\ObjectType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserState\CheckpointSets\UserStateCheckpointSets;

trait UserStateObjectTypeFieldResolverTrait
{
    protected function getValidationCheckpointSets(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
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
        array $checkpointSet,
        Error $error,
        string $errorMessage,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): string {
        if ($checkpointSet !== UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER) {
            return $errorMessage;
        }
        $translationAPI = TranslationAPIFacade::getInstance();
        return sprintf(
            $translationAPI->__('You must be logged in to access field \'%s\' for type \'%s\'', ''),
            $fieldName,
            $objectTypeResolver->getMaybeNamespacedTypeName()
        );
    }
}
