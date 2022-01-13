<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class PoP_SocialNetwork_Notifications_Hook_Tags /* extends AAL_Hook_Base*/
{
    public function __construct()
    {

        // Subscribed to/Unsubscribed from
        \PoP\Root\App::getHookManager()->addAction('gd_subscribetotag', array($this, 'subscribedtoTag'));
        \PoP\Root\App::getHookManager()->addAction('gd_unsubscribefromtag', array($this, 'unsubscribedfromTag'));

        // parent::__construct();
    }

    public function subscribedtoTag($subscribedto_tag_id)
    {
        $this->subscribedtounsubscribedfromTag($subscribedto_tag_id, AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG);
    }

    public function unsubscribedfromTag($unsubscribedfrom_tag_id)
    {
        $this->subscribedtounsubscribedfromTag($unsubscribedfrom_tag_id, AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG);
    }

    public function subscribedtounsubscribedfromTag($tag_id, $action)
    {
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        $tag = $postTagTypeAPI->getTag($tag_id);
        PoP_Notifications_Utils::insertLog(
            array(
                'action'      => $action,
                'object_type' => 'Taxonomy',
                'object_subtype' => 'Tag',
                'user_id'     => \PoP\Root\App::getState('current-user-id'),
                'object_id'   => $tag_id,
                'object_name' => $applicationtaxonomyapi->getTagSymbolName($tag),
            )
        );
    }
}
