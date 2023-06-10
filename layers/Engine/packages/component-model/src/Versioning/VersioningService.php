<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Versioning;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Constants\ConfigurationValues;
use PoP\ComponentModel\Constants\Constants;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\FeedbackItemProviders\WarningFeedbackItemProvider;
use PoP\ComponentModel\Feedback\GeneralFeedback;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;

class VersioningService implements VersioningServiceInterface
{
    use BasicServiceTrait;

    /**
     * @var array<string,array<string,string>>|null
     */
    private ?array $versionConstraintsForFields = null;
    /**
     * @var array<string,string>|null
     */
    private ?array $versionConstraintsForDirectives = null;

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
                        new FeedbackItemResolution(
                            WarningFeedbackItemProvider::class,
                            WarningFeedbackItemProvider::W1,
                            [
                                $typeField,
                            ]
                        )
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
    public function getVersionConstraintsForField(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): ?string {
        if ($this->versionConstraintsForFields === null) {
            $this->initializeVersionConstraintsForFields();
        }
        $fieldName = $field->getName();
        return $this->versionConstraintsForFields[$objectTypeResolver->getNamespacedTypeName()][$fieldName]
            ?? $this->versionConstraintsForFields[$objectTypeResolver->getTypeName()][$fieldName]
            ?? $this->versionConstraintsForFields[ConfigurationValues::ANY][$fieldName]
            ?? null;
    }

    /**
     * Indicates the version constraints for specific directives in the schema
     */
    public function getVersionConstraintsForDirective(DirectiveResolverInterface $directive): ?string
    {
        if ($this->versionConstraintsForDirectives === null) {
            $this->versionConstraintsForDirectives = App::getState('directive-version-constraints') ?? [];
        }
        return $this->versionConstraintsForDirectives[$directive->getDirectiveName()] ?? null;
    }
}
