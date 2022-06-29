<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUserMutations\MutationResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_EmailSender_Utils;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

class ContactUserMutationResolver extends AbstractMutationResolver
{
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }

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

        if (empty($withArgumentsAST->getArgumentValue('message'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Message cannot be empty.', 'pop-genericforms');
        }

        if (empty($withArgumentsAST->getArgumentValue('target-id'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('The requested user cannot be empty.', 'pop-genericforms');
        } else {
            $target = $this->getUserTypeAPI()->getUserByID($withArgumentsAST->getArgumentValue('target-id'));
            if (!$target) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->__('The requested user does not exist.', 'pop-genericforms');
            }
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals($withArgumentsAST): void
    {
        App::doAction('pop_contactuser', $withArgumentsAST);
    }

    protected function doExecute($withArgumentsAST)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $websitename = $cmsapplicationapi->getSiteName();
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $websitename,
            $withArgumentsAST->getArgumentValue('subject') ? $withArgumentsAST->getArgumentValue('subject') : sprintf(
                $this->__('%s sends you a message', 'pop-genericforms'),
                $withArgumentsAST->getArgumentValue('name')
            )
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            sprintf(
                $this->__('You have been sent a message from a user in %s', 'pop-genericforms'),
                $websitename
            )
        ) . sprintf(
            $placeholder,
            $this->__('Name', 'pop-genericforms'),
            $withArgumentsAST->getArgumentValue('name')
        ) . sprintf(
            $placeholder,
            $this->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $withArgumentsAST->getArgumentValue('email')
            )
        ) . sprintf(
            $placeholder,
            $this->__('Subject', 'pop-genericforms'),
            $withArgumentsAST->getArgumentValue('subject')
        ) . sprintf(
            $placeholder,
            $this->__('Message', 'pop-genericforms'),
            $withArgumentsAST->getArgumentValue('message')
        );

        return PoP_EmailSender_Utils::sendemailToUser($withArgumentsAST->getArgumentValue('target-id'), $subject, $msg);
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
