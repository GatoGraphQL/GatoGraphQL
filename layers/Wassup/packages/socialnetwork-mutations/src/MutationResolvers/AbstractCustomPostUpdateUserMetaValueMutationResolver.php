<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class AbstractCustomPostUpdateUserMetaValueMutationResolver extends AbstractUpdateUserMetaValueMutationResolver
{
    protected function eligible($post)
    {
        return true;
    }

    public function validateErrors(array $form_data): ?array
    {
        $errors = parent::validateErrors($form_data);
        if (!$errors) {
            $target_id = $form_data['target_id'];

            // Make sure the post exists
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            $target = $customPostTypeAPI->getCustomPost($target_id);
            if (!$target) {
                $errors[] = TranslationAPIFacade::getInstance()->__('The requested post does not exist.', 'pop-coreprocessors');
            } else {
                // Make sure this target accepts this functionality. Eg: Not all posts can be Recommended or Up/Down-voted.
                // Discussion can be recommended only, Highlight up/down-voted only
                if (!$this->eligible($target)) {
                    $errors[] = TranslationAPIFacade::getInstance()->__('The requested functionality does not apply on this post.', 'pop-coreprocessors');
                }
            }
        }
        return $errors;
    }

    protected function getRequestKey()
    {
        return \PoPSchema\Posts\Constants\InputNames::POST_ID;
    }

    protected function additionals($target_id, $form_data)
    {
        HooksAPIFacade::getInstance()->doAction('gd_updateusermetavalue:post', $target_id, $form_data);
        parent::additionals($target_id, $form_data);
    }
}
