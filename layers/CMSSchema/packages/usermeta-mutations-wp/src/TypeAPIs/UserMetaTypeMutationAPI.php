<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutationsWP\TypeAPIs;

use PoPCMSSchema\MetaMutationsWP\TypeAPIs\EntityMetaTypeMutationAPITrait;
use PoPCMSSchema\UserMetaMutations\Exception\UserMetaCRUDMutationException;
use PoPCMSSchema\UserMetaMutations\TypeAPIs\AbstractUserMetaTypeMutationAPI;
use PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use WP_Error;

use function add_user_meta;
use function delete_user_meta;
use function update_user_meta;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserMetaTypeMutationAPI extends AbstractUserMetaTypeMutationAPI
{
    use EntityMetaTypeMutationAPITrait;

    private ?UserMetaTypeAPIInterface $userMetaTypeAPI = null;

    final protected function getUserMetaTypeAPI(): UserMetaTypeAPIInterface
    {
        if ($this->userMetaTypeAPI === null) {
            /** @var UserMetaTypeAPIInterface */
            $userMetaTypeAPI = $this->instanceManager->getInstance(UserMetaTypeAPIInterface::class);
            $this->userMetaTypeAPI = $userMetaTypeAPI;
        }
        return $this->userMetaTypeAPI;
    }

    /**
     * @throws UserMetaCRUDMutationException If the meta key is protected
     */
    protected function assertMetaKeyIsNotProtected(string $key): void
    {
        if (!$this->getUserMetaTypeAPI()->isMetaKeyProtected($key)) {
            return;
        }
        throw $this->getEntityMetaCRUDMutationException(
            sprintf(
                $this->__('Meta key \'%s\' is not allowed', 'gatographql'),
                $key
            )
        );
    }

    protected function executeAddEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        bool $single = false,
    ): int|false|WP_Error {
        $this->assertMetaKeyIsNotProtected($key);
        return add_user_meta((int) $entityID, $key, $value, $single);
    }

    protected function executeUpdateEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value,
        mixed $prevValue = null,
    ): int|bool|WP_Error {
        $this->assertMetaKeyIsNotProtected($key);
        return update_user_meta((int) $entityID, $key, $value, $prevValue ?? '');
    }

    protected function executeDeleteEntityMeta(
        string|int $entityID,
        string $key,
        mixed $value = null,
    ): bool {
        $this->assertMetaKeyIsNotProtected($key);
        return delete_user_meta((int) $entityID, $key, $value ?? '');
    }
}
