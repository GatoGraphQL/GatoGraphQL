<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Versioning;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Constants\Constants;
use PoP\ComponentModel\Feedback\GeneralFeedback;
use PoP\ComponentModel\FeedbackItemProviders\FeedbackItemProvider;
use PoP\Root\Services\BasicServiceTrait;

class VersioningService implements VersioningServiceInterface
{
    use BasicServiceTrait;

    private ?array $versionConstraintsForFields = null;
    private ?array $versionConstraintsForDirectives = null;

    private ?FeedbackItemProvider $feedbackItemProvider = null;

    final public function setFeedbackItemProvider(FeedbackItemProvider $feedbackItemProvider): void
    {
        $this->feedbackItemProvider = $feedbackItemProvider;
    }
    final protected function getFeedbackItemProvider(): FeedbackItemProvider
    {
        return $this->feedbackItemProvider ??= $this->instanceManager->getInstance(FeedbackItemProvider::class);
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
                        $this->getFeedbackItemProvider()->getMessage(FeedbackItemProvider::W2, $typeField),
                        $this->getFeedbackItemProvider()->getNamespacedCode(FeedbackItemProvider::W2)
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
