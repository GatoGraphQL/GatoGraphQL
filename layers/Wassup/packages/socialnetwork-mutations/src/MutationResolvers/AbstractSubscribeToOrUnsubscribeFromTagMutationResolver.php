<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\App;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;

abstract class AbstractSubscribeToOrUnsubscribeFromTagMutationResolver extends AbstractUpdateUserMetaValueMutationResolver
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }

    public function validateErrors(WithArgumentsInterface $withArgumentsAST): array
    {
        $errors = parent::validateErrors($withArgumentsAST);
        if (!$errors) {
            $target_id = $withArgumentsAST->getArgumentValue('target_id');

            // Make sure the post exists
            $target = $this->getPostTagTypeAPI()->getTag($target_id);
            if (!$target) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->__('The requested topic/tag does not exist.', 'pop-coreprocessors');
            }
        }
        return $errors;
    }

    protected function additionals($target_id, $withArgumentsAST): void
    {
        App::doAction('gd_subscritetounsubscribefrom_tag', $target_id, $withArgumentsAST);
        parent::additionals($target_id, $withArgumentsAST);
    }
}
