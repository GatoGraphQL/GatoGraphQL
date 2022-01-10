<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Types\Status;

HooksAPIFacade::getInstance()->addFilter('gd-createupdate-post:execute:successstring', 'gdPppCreateupdateAddPreviewLink', 10, 3);
function gdPppCreateupdateAddPreviewLink($success_string, $post_id, $status)
{
    if (in_array($status, array(Status::DRAFT, Status::PENDING))) {
        $pluginapi = PoP_PreviewContent_FunctionsAPIFactory::getInstance();
        $previewurl = $pluginapi->getPreviewLink($post_id);

        // Allow to inject data-sw-networkfirst="true"
        $previewurl_params = HooksAPIFacade::getInstance()->applyFilters('gd_ppp_previewurl_link_params', '');
        if ($previewurl) {
            $previewurl_target = HooksAPIFacade::getInstance()->applyFilters('gd_ppp_previewurl_target', \PoP\ConfigurationComponentModel\Constants\Targets::MAIN);
            $success_string .= sprintf(
                ' <a href="%1$s" target="%2$s" class="btn btn-xs btn-primary" %4$s><i class="fa fa-fw fa-eye"></i>%3$s</a>',
                $previewurl,
                $previewurl_target,
                TranslationAPIFacade::getInstance()->__('Preview', 'ppp-pop'),
                $previewurl_params
            );
        }
    }

    return $success_string;
}

HooksAPIFacade::getInstance()->addFilter(
    AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
    'gdPppAddPublicPreview',
    10,
    1
);
function gdPppAddPublicPreview($post_id)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    $post_status = $customPostTypeAPI->getStatus($post_id);
    if (in_array($post_status, array(Status::DRAFT, Status::PENDING, Status::PUBLISHED))) {
        $pluginapi = PoP_PreviewContent_FunctionsAPIFactory::getInstance();

        // Add the post to have "public preview"
        if (in_array($post_status, array(Status::DRAFT, Status::PENDING))) {
            $pluginapi->setPreview($post_id);
        }
        // Remove it, so published posts don't have the "public preview" enabled anymore
        elseif (($post_status == Status::PUBLISHED)) {
            $pluginapi->removePreview($post_id);
        }
    }
}
