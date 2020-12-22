<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserState\CheckpointSets\UserStateCheckpointSets;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\ErrorHandling\Error;

trait UserStateFieldResolverTrait
{
    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<array>|null A checkpoint set, or null
     */
    protected function getValidationCheckpoints(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): ?array {
        return UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    protected function getValidationCheckpointsErrorMessage(
        string $errorMessage,
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        Error $error
    ): string {
        $translationAPI = TranslationAPIFacade::getInstance();
        return sprintf(
            $translationAPI->__('You must be logged in to access field \'%s\' for type \'%s\'', ''),
            $fieldName,
            $typeResolver->getMaybeNamespacedTypeName()
        );
    }
}
