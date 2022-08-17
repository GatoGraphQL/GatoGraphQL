<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;

class ObjectResolutionFeedbackStore
{
    /** @var ObjectResolutionFeedbackInterface[] */
    private array $errors = [];
    /** @var ObjectResolutionFeedbackInterface[] */
    private array $warnings = [];
    /** @var ObjectResolutionFeedbackInterface[] */
    private array $deprecations = [];
    /** @var ObjectResolutionFeedbackInterface[] */
    private array $notices = [];
    /** @var ObjectResolutionFeedbackInterface[] */
    private array $suggestions = [];
    /** @var ObjectResolutionFeedbackInterface[] */
    private array $logs = [];

    public function incorporate(
        ObjectResolutionFeedbackStore $objectResolutionFeedbackStore,
    ): void {
        $this->errors = array_merge(
            $this->errors,
            $objectResolutionFeedbackStore->getErrors()
        );
        $this->warnings = array_merge(
            $this->warnings,
            $objectResolutionFeedbackStore->getWarnings()
        );
        $this->deprecations = array_merge(
            $this->deprecations,
            $objectResolutionFeedbackStore->getDeprecations()
        );
        $this->notices = array_merge(
            $this->notices,
            $objectResolutionFeedbackStore->getNotices()
        );
        $this->suggestions = array_merge(
            $this->suggestions,
            $objectResolutionFeedbackStore->getSuggestions()
        );
        $this->logs = array_merge(
            $this->logs,
            $objectResolutionFeedbackStore->getLogs()
        );
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    public function incorporateFromObjectTypeFieldResolutionFeedbackStore(
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        RelationalTypeResolverInterface $relationalTypeResolver,
        Directive $directive,
        array $idFieldSet,
    ): void {
        foreach ($objectTypeFieldResolutionFeedbackStore->getErrors() as $objectTypeFieldResolutionFeedbackError) {
            $this->errors[] = ObjectResolutionFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackError,
                $relationalTypeResolver,
                $directive,
                $idFieldSet,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getWarnings() as $objectTypeFieldResolutionFeedbackWarning) {
            $this->warnings[] = ObjectResolutionFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackWarning,
                $relationalTypeResolver,
                $directive,
                $idFieldSet,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getDeprecations() as $objectTypeFieldResolutionFeedbackDeprecation) {
            $this->deprecations[] = ObjectResolutionFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackDeprecation,
                $relationalTypeResolver,
                $directive,
                $idFieldSet,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getNotices() as $objectTypeFieldResolutionFeedbackNotice) {
            $this->notices[] = ObjectResolutionFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackNotice,
                $relationalTypeResolver,
                $directive,
                $idFieldSet,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getSuggestions() as $objectTypeFieldResolutionFeedbackSuggestion) {
            $this->suggestions[] = ObjectResolutionFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackSuggestion,
                $relationalTypeResolver,
                $directive,
                $idFieldSet,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getLogs() as $objectTypeFieldResolutionFeedbackLog) {
            $this->logs[] = ObjectResolutionFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackLog,
                $relationalTypeResolver,
                $directive,
                $idFieldSet,
            );
        }
    }

    public function getErrorCount(): int
    {
        return count($this->getErrors());
    }

    /**
     * @return ObjectResolutionFeedbackInterface[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(ObjectResolutionFeedbackInterface $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param ObjectResolutionFeedbackInterface[] $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return ObjectResolutionFeedbackInterface[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function addWarning(ObjectResolutionFeedbackInterface $warning): void
    {
        $this->warnings[] = $warning;
    }

    /**
     * @param ObjectResolutionFeedbackInterface[] $warnings
     */
    public function setWarnings(array $warnings): void
    {
        $this->warnings = $warnings;
    }

    /**
     * @return ObjectResolutionFeedbackInterface[]
     */
    public function getDeprecations(): array
    {
        return $this->deprecations;
    }

    public function addDeprecation(ObjectResolutionFeedbackInterface $deprecation): void
    {
        $this->deprecations[] = $deprecation;
    }

    /**
     * @param ObjectResolutionFeedbackInterface[] $deprecations
     */
    public function setDeprecations(array $deprecations): void
    {
        $this->deprecations = $deprecations;
    }

    /**
     * @return ObjectResolutionFeedbackInterface[]
     */
    public function getNotices(): array
    {
        return $this->notices;
    }

    public function addNotice(ObjectResolutionFeedbackInterface $notice): void
    {
        $this->notices[] = $notice;
    }

    /**
     * @param ObjectResolutionFeedbackInterface[] $notices
     */
    public function setNotices(array $notices): void
    {
        $this->notices = $notices;
    }

    /**
     * @return ObjectResolutionFeedbackInterface[]
     */
    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    public function addSuggestion(ObjectResolutionFeedbackInterface $suggestion): void
    {
        $this->suggestions[] = $suggestion;
    }

    /**
     * @param ObjectResolutionFeedbackInterface[] $suggestions
     */
    public function setSuggestions(array $suggestions): void
    {
        $this->suggestions = $suggestions;
    }

    /**
     * @return ObjectResolutionFeedbackInterface[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function addLog(ObjectResolutionFeedbackInterface $log): void
    {
        $this->logs[] = $log;
    }

    /**
     * @param ObjectResolutionFeedbackInterface[] $logs
     */
    public function setLogs(array $logs): void
    {
        $this->logs = $logs;
    }
}
