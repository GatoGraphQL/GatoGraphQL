<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeAPIs;

use PoPCMSSchema\CustomPostMetaMutations\Exception\CustomPostMetaCRUDMutationException;
use PoPCMSSchema\CustomPostMetaMutations\TypeAPIs\CustomPostMetaTypeMutationAPIInterface;
use PoPCMSSchema\MetaMutations\TypeAPIs\AbstractEntityMetaTypeMutationAPI;

abstract class AbstractCustomPostMetaTypeMutationAPI extends AbstractEntityMetaTypeMutationAPI implements CustomPostMetaTypeMutationAPIInterface
{
    /**
     * @phpstan-return class-string<CustomPostMetaCRUDMutationException>
     */
    protected function getEntityMetaCRUDMutationExceptionClass(): string
    {
        return CustomPostMetaCRUDMutationException::class;
    }
}
