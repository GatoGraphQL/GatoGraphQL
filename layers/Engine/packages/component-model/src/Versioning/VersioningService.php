<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Versioning;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\GeneralFeedback;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\Root\Services\BasicServiceTrait;

class VersioningService implements VersioningServiceInterface
{
    use BasicServiceTrait;

    /**
     * Token used to separate the type from the field for setting version constraints
     */
    private const TYPE_FIELD_SEPARATOR = '.';

    private ?array $versionConstraintsForFields = null;
    private ?array $versionConstraintsForDirectives = null;

    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;

    final public function setFeedbackMessageStore(FeedbackMessageStoreInterface $feedbackMessageStore): void
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
    }
    final protected function getFeedbackMessageStore(): FeedbackMessageStoreInterface
    {
        return $this->feedbackMessageStore ??= $this->instanceManager->getInstance(FeedbackMessageStoreInterface::class);
    }

    /**
     * Initialize the dictionary with the version constraints for specific fields in the schema
     */
    protected function initializeVersionConstraintsForFields(): void
    {
        // Iterate through entries in `fieldVersionConstraints` and set them into a dictionary
        $this->versionConstraintsForFields = [];
        $generalWarningMessages = [];
        foreach ((App::getState('field-version-constraints') ?? []) as $typeField => $versionConstraint) {
            // All fields are defined as "$type.$fieldName". If not, it's an error
            $entry = explode(self::TYPE_FIELD_SEPARATOR, $typeField);
            if (count($entry) != 2) {
                $generalWarningMessages[] = sprintf(
                    $this->__('URL param \'fieldVersionConstraints\' expects the type and field name separated by \'%s\' (eg: \'%s\'), so the following value has been ignored: \'%s\'', 'component-model'),
                    self::TYPE_FIELD_SEPARATOR,
                    '?fieldVersionConstraints[Post.title]=^0.1',
                    $typeField
                );
                continue;
            }
            $maybeNamespacedTypeName = $entry[0];
            $fieldName = $entry[1];
            $this->versionConstraintsForFields[$maybeNamespacedTypeName][$fieldName] = $versionConstraint;
        }
        if ($generalWarningMessages) {
            $generalFeedbackStore = App::getFeedbackStore()->generalFeedbackStore;
            foreach ($generalWarningMessages as $warningMessage) {
                $generalFeedbackStore->addGeneralWarning(
                    new GeneralFeedback(
                        $warningMessage,
                        null
                    )
                );
            }
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
