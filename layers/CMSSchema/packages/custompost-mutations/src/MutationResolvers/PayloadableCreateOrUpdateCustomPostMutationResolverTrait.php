<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;

trait PayloadableCreateOrUpdateCustomPostMutationResolverTrait
{
    use PayloadableCustomPostMutationResolverTrait;

    abstract protected function getObjectDictionary(): ObjectDictionaryInterface;

    protected function createAndStoreErrorPayloadFromObjectTypeFieldResolutionFeedback(
        ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
    ): ErrorPayloadInterface {
        $errorPayload = $this->createErrorPayloadFromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback);
        $this->getObjectDictionary()->set(
            get_class($errorPayload),
            $errorPayload->getID(),
            $errorPayload,
        );
        return $errorPayload;
    }
}
