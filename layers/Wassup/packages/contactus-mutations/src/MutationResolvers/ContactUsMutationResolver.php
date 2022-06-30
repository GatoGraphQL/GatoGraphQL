<?php

declare(strict_types=1);

namespace PoPSitesWassup\ContactUsMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_EmailSender_Utils;
use PoP\Root\Exception\AbstractException;
use PoP\Root\App;
use PoP\Application\FunctionAPIFactory;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class ContactUsMutationResolver extends AbstractMutationResolver
{
    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        $errors = [];
        if (empty($mutationDataProvider->getArgumentValue('name'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Your name cannot be empty.', 'pop-genericforms');
        }
        if (empty($mutationDataProvider->getArgumentValue('email'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Email cannot be empty.', 'pop-genericforms');
        } elseif (!filter_var($mutationDataProvider->getArgumentValue('email'), FILTER_VALIDATE_EMAIL)) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Email format is incorrect.', 'pop-genericforms');
        }
        if (empty($mutationDataProvider->getArgumentValue('message'))) {
            // @todo Migrate from string to FeedbackItemProvider
            // $errors[] = new FeedbackItemResolution(
            //     MutationErrorFeedbackItemProvider::class,
            //     MutationErrorFeedbackItemProvider::E1,
            // );
            $errors[] = $this->__('Message cannot be empty.', 'pop-genericforms');
        }
        return $errors;
    }

    /**
     * Function to override
     */
    protected function additionals(MutationDataProviderInterface $mutationDataProvider): void
    {
        App::doAction('pop_contactus', $mutationDataProvider);
    }

    protected function doExecute(MutationDataProviderInterface $mutationDataProvider)
    {
        $cmsapplicationapi = FunctionAPIFactory::getInstance();
        $to = PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $subject = sprintf(
            $this->__('[%s]: %s', 'pop-genericforms'),
            $cmsapplicationapi->getSiteName(),
            $this->__('Contact us', 'pop-genericforms')
        );
        $placeholder = '<p><b>%s:</b> %s</p>';
        $msg = sprintf(
            '<p>%s</p>',
            $this->__('New contact us submission', 'pop-genericforms')
        ) . sprintf(
            $placeholder,
            $this->__('Name', 'pop-genericforms'),
            $mutationDataProvider->getArgumentValue('name')
        ) . sprintf(
            $placeholder,
            $this->__('Email', 'pop-genericforms'),
            sprintf(
                '<a href="mailto:%1$s">%1$s</a>',
                $mutationDataProvider->getArgumentValue('email')
            )
        ) . sprintf(
            $placeholder,
            $this->__('Subject', 'pop-genericforms'),
            $mutationDataProvider->getArgumentValue('subject')
        ) . sprintf(
            $placeholder,
            $this->__('Message', 'pop-genericforms'),
            $mutationDataProvider->getArgumentValue('message')
        );

        return PoP_EmailSender_Utils::sendEmail($to, $subject, $msg);
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        $result = $this->doExecute($mutationDataProvider);

        // Allow for additional operations
        $this->additionals($mutationDataProvider);

        return $result;
    }
}
