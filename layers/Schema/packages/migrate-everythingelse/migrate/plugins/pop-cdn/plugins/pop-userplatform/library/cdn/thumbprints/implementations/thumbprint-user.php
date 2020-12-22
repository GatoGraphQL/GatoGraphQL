<?php
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

define('POP_CDN_THUMBPRINT_USER', 'user');

class PoP_CDN_Thumbprint_User extends PoP_CDN_ThumbprintBase
{
    public function getName()
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

    public function executeQuery($query, array $options = [])
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $options['return-type'] = ReturnTypes::IDS;
        return $cmsusersapi->getUsers($query, $options);
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
