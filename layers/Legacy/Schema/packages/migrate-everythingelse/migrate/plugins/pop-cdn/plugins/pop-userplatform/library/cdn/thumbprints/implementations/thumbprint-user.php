<?php
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

define('POP_CDN_THUMBPRINT_USER', 'user');

class PoP_CDN_Thumbprint_User extends PoP_CDN_ThumbprintBase
{
    public function getName(): string
    {
        return POP_CDN_THUMBPRINT_USER;
    }

    public function getQuery()
    {
        return array(
            // 'fields' => 'ID',
            'limit' => 1,
            // Moved under WordPress-specific file
            // 'meta_key' => \PoPSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_LASTEDITED),
            // 'orderby' => 'meta_value',
            'orderby' => NameResolverFacade::getInstance()->getName('popcomponent:userplatform:dbcolumn:orderby:users:lastediteddate'),
            'order' => 'DESC',
            'role' => GD_ROLE_PROFILE,
        );
    }

    public function executeQuery($query, array $options = []): array
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
        return $userTypeAPI->getUsers($query, $options);
    }

    public function getTimestamp($user_id)
    {
        return \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_METAKEY_PROFILE_LASTEDITED, true);
    }
}

/**
 * Initialize
 */
new PoP_CDN_Thumbprint_User();
