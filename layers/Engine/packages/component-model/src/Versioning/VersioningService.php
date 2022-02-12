<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Versioning;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Constants\Constants;
use PoP\ComponentModel\Feedback\GeneralFeedback;
use PoP\ComponentModel\FeedbackMessageProviders\FeedbackMessageProvider;
use PoP\Root\Services\BasicServiceTrait;

class VersioningService implements VersioningServiceInterface
{
    use BasicServiceTrait;

    private ?array $versionConstraintsForFields = null;
    private ?array $versionConstraintsForDirectives = null;

    private ?FeedbackMessageProvider $feedbackMessageProvider = null;

    final public function setFeedbackMessageProvider(FeedbackMessageProvider $feedbackMessageProvider): void
    {
        $this->feedbackMessageProvider = $feedbackMessageProvider;
    }
    final protected function getFeedbackMessageProvider(): FeedbackMessageProvider
    {
        return $this->feedbackMessageProvider ??= $this->instanceManager->getInstance(FeedbackMessageProvider::class);
    }

    /**
     * Initialize the dictionary with the version constraints for specific fields in the schema
     */
    protected function initializeVersionConstraintsForFields(): void
    {
        $generalFeedbackStore = App::getFeedbackStore()->generalFeedbackStore;

        // Iterate through entries in `fieldVersionConstraints` and set them into a dictionary
        $this->versionConstraintsForFields = [];
        foreach ((App::getState('field-version-constraints') ?? []) as $typeField => $versionConstraint) {
            // All fields are defined as "$type.$fieldName". If not, it's an error
            $entry = explode(Constants::TYPE_FIELD_SEPARATOR, $typeField);
            if (count($entry) !== 2) {
                $generalFeedbackStore->addWarning(
                    new GeneralFeedback(
                        $this->getFeedbackMessageProvider()->getMessage(FeedbackMessageProvider::W1, $typeField),
                        $this->getFeedbackMessageProvider()->getNamespacedCode(FeedbackMessageProvider::W1)
                    )
                );
                continue;
            }
            $maybeNamespacedTypeName = $entry[0];
            $fieldName = $entry[1];
            $this->versionConstraintsForFields[$maybeNamespacedTypeName][$fieldName] = $versionConstraint;
        }
    }

    /**
     * Indicates the version constraints for specific fields in the schema
     */
    public function getVersionConstraintsForField(string $maybeNamespacedTypeName, string $fieldName): ?string
    {
        if ($this->versionConstraintsForFields === null) {
            $this->initializeVersionConstraintsForFields();
        }
        return $this->versionConstraintsForFields[$maybeNamespacedTypeName][$fieldName] ?? null;
    }

    /**
     * Indicates the version constraints for specific directives in the schema
     */
    public function getVersionConstraintsForDirective(string $directiveName): ?string
    {
        if ($this->versionConstraintsForDirectives === null) {
            $this->versionConstraintsForDirectives = App::getState('directive-version-constraints');
        }
        return $this->versionConstraintsForDirectives[$directiveName] ?? null;
    }
}
