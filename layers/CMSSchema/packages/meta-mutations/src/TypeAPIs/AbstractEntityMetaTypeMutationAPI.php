<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeAPIs;

use PoPCMSSchema\MetaMutations\Exception\EntityMetaCRUDMutationException;
use PoPCMSSchema\SchemaCommonsWP\TypeAPIs\TypeMutationAPITrait;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;

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
}
