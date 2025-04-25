<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeAPIs;

use PoPCMSSchema\MetaMutations\Exception\EntityMetaCRUDMutationException;
use PoPCMSSchema\SchemaCommonsWP\TypeAPIs\TypeMutationAPITrait;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;
use stdClass;

abstract class AbstractEntityMetaTypeMutationAPI extends AbstractBasicService implements EntityMetaTypeMutationAPIInterface
{
    use TypeMutationAPITrait;

    protected function handleMaybeError(
        int|bool|WP_Error $result,
    ): void {
        if (!($result instanceof WP_Error)) {
            return;
        }

        /** @var WP_Error */
        $wpError = $result;
        throw $this->getEntityMetaCRUDMutationException($wpError);
    }

    protected function getEntityMetaCRUDMutationException(WP_Error|string $error): EntityMetaCRUDMutationException
    {
        $entityMetaCRUDMutationExceptionClass = $this->getEntityMetaCRUDMutationExceptionClass();
        if (is_string($error)) {
            return new $entityMetaCRUDMutationExceptionClass($error);
        }
        /** @var WP_Error */
        $wpError = $error;
        return new $entityMetaCRUDMutationExceptionClass(
            $wpError->get_error_message(),
            $wpError->get_error_code() ? $wpError->get_error_code() : null,
            $this->getWPErrorData($wpError),
        );
    }

    /**
     * @phpstan-return class-string<EntityMetaCRUDMutationException>
     */
    abstract protected function getEntityMetaCRUDMutationExceptionClass(): string;

    /**
     * @param array<string,mixed[]|null> $entries
     * @throws EntityMetaCRUDMutationException If there was an error
     */
    public function setEntityMeta(
        string|int $entityID,
        array $entries,
    ): void {
        foreach ($entries as $key => $values) {
            if ($values === null) {
                $this->executeDeleteEntityMeta($entityID, $key);
                continue;
            }

            $numberItems = count($values);
            if ($numberItems === 0) {
                continue;
            }

            /**
             * If there are 2 or more items, then use `add` to add them.
             * If there is only 1 item, then use `update` to update it.
             */
            if ($numberItems === 1) {
                $value = $values[0];
                if ($value === null) {
                    $this->executeDeleteEntityMeta($entityID, $key);
                    continue;
                }
                $this->executeUpdateEntityMeta($entityID, $key, $value);
                continue;
            }

            // $numberItems > 1
            $this->executeDeleteEntityMeta($entityID, $key);
            foreach ($values as $value) {
                $this->executeAddEntityMeta($entityID, $key, $value, false);
            }
        }
    }

    /**
     * @return int The term_id of the newly created term
     * @throws EntityMetaCRUDMutationException If there was an error
     */
    public function addEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int {
        $value = $this->maybeConvertStdClassToArray($value);
        $result = $this->executeAddEntityMeta($entityID, $key, $value, $single);
        if ($result === false) {
            throw $this->getEntityMetaCRUDMutationException(
                \__('Error adding meta', 'meta-mutations')
            );
        }
        $this->handleMaybeError($result);
        /** @var int $result */
        return $result;
    }

    /**
     * Do not store stdClass objects in the database, convert them to arrays
     */
    protected function maybeConvertStdClassToArray(mixed $value): mixed
    {
        if (!(is_array($value) || ($value instanceof stdClass))) {
            return $value;
        }
        return MethodHelpers::recursivelyConvertStdClassToAssociativeArray($value);
    }

    abstract protected function executeAddEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int|false|WP_Error;

    /**
     * @return string|int|bool the ID of the created meta entry if it didn't exist, or `true` if it did exist
     * @throws EntityMetaCRUDMutationException If there was an error (eg: entity does not exist)
     */
    public function updateEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): string|int|bool {
        $value = $this->maybeConvertStdClassToArray($value);
        $result = $this->executeUpdateEntityMeta($entityID, $key, $value, $prevValue ?? '');
        $this->handleMaybeError($result);
        if ($result === false) {
            throw $this->getEntityMetaCRUDMutationException(
                \__('Error updating meta', 'meta-mutations')
            );
        }
        /** @var int|bool $result */
        return $result;
    }

    abstract protected function executeUpdateEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): int|bool|WP_Error;

    /**
     * @throws EntityMetaCRUDMutationException If there was an error (eg: entity does not exist)
     */
    public function deleteEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value = null,
    ): void {
        $result = $this->executeDeleteEntityMeta($entityID, $key, $value);
        $this->handleMaybeError($result);
        if ($result === false) {
            throw $this->getEntityMetaCRUDMutationException(
                \__('Error deleting meta', 'meta-mutations')
            );
        }
    }

    abstract protected function executeDeleteEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value = null,
    ): bool;
}
