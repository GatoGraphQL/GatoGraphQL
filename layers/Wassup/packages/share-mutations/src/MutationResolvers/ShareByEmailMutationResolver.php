<?php

declare(strict_types=1);

namespace PoPSitesWassup\ShareMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_EmailSender_Utils;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class ShareByEmailMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(WithArgumentsInterface $withArgumentsAST): array
    {
        $errors = [];
        if (empty($withArgumentsAST->getArgumentValue('name'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Your name cannot be empty.', 'pop-genericforms');
        }

        if (empty($withArgumentsAST->getArgumentValue('email'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($withArgumentsAST->getArgumentValue('email'), FILTER_VALIDATE_EMAIL)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Email format is incorrect.', 'pop-genericforms');
        }

        if (empty($withArgumentsAST->getArgumentValue('target-url'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The shared-page URL cannot be empty.', 'pop-genericforms');
        }

        if (empty($withArgumentsAST->getArgumentValue('target-title'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The shared-page title cannot be empty.', 'pop-genericforms');
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals(WithArgumentsInterface $withArgumentsAST): void
    {
        App::doAction('pop_sharebyemail', $withArgumentsAST);
    }

    protected function doExecute(WithArgumentsInterface $withArgumentsAST)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            sprintf(
                $this->__('%s is sharing with you: %s', 'pop-genericforms'),
                $withArgumentsAST->getArgumentValue('name'),
                $withArgumentsAST->getArgumentValue('target-title')
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            sprintf(
                $this->__('%s is sharing with you: <a href="%s">%s</a>', 'pop-genericforms'),
                $withArgumentsAST->getArgumentValue('name'),
                $withArgumentsAST->getArgumentValue('target-url'),
                $withArgumentsAST->getArgumentValue('target-title')
            )
        ) . ($withArgumentsAST->getArgumentValue('message') ? sprintf(
            $placeholder,
            $this->__('Additional message', 'pop-genericforms'),
            $withArgumentsAST->getArgumentValue('message')
        ) : '');

        return PoP_EmailSender_Utils::sendEmail($withArgumentsAST->getArgumentValue('email'), $subject, $msg);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(WithArgumentsInterface $withArgumentsAST): mixed
    {
        $result = $this->doExecute($withArgumentsAST);

        // Allow for additional operations
        $this->additionals($withArgumentsAST);

        return $result;
    }
}
