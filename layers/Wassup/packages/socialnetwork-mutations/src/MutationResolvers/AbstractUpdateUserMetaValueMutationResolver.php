<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

abstract class AbstractUpdateUserMetaValueMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(WithArgumentsInterface $withArgumentsAST): array
    {
        $errors = [];
        $target_id = $withArgumentsAST->getArgumentValue('target_id');
        if (!$target_id) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('This URL is incorrect.', 'pop-coreprocessors');
        }
        return $errors;
    }

    protected function additionals($target_id, $withArgumentsAST): void
    {
        App::doAction('gd_updateusermetavalue', $target_id, $withArgumentsAST);
    }

    /**
     * @throws AbstractException In case of error
     */
    protected function update(WithArgumentsInterface $withArgumentsAST): string | int
    {
        $target_id = $withArgumentsAST->getArgumentValue('target_id');
        return $target_id;
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(WithArgumentsInterface $withArgumentsAST): mixed
    {
        $target_id = $this->update($withArgumentsAST);
        $this->additionals($target_id, $withArgumentsAST);

        return $target_id;
    }
}
