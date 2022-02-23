<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class SchemaFeedbackStore
{
    /** @var SchemaFeedbackInterface[] */
    private array $errors = [];
    /** @var SchemaFeedbackInterface[] */
    private array $warnings = [];
    /** @var SchemaFeedbackInterface[] */
    private array $deprecations = [];
    /** @var SchemaFeedbackInterface[] */
    private array $notices = [];
    /** @var SchemaFeedbackInterface[] */
    private array $logs = [];
    /** @var SchemaFeedbackInterface[] */
    private array $traces = [];

    public function incorporate(
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
        ?string $directive = null,
    ): void {
        foreach ($objectTypeFieldResolutionFeedbackStore->getErrors() as $objectTypeFieldResolutionFeedbackError) {
            $this->errors[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackError,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getWarnings() as $objectTypeFieldResolutionFeedbackWarning) {
            $this->warnings[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackWarning,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getDeprecations() as $objectTypeFieldResolutionFeedbackDeprecation) {
            $this->deprecations[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackDeprecation,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getNotices() as $objectTypeFieldResolutionFeedbackNotice) {
            $this->notices[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackNotice,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getLogs() as $objectTypeFieldResolutionFeedbackLog) {
            $this->logs[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackLog,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getTraces() as $objectTypeFieldResolutionFeedbackTrace) {
            $this->traces[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackTrace,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(SchemaFeedbackInterface $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param SchemaFeedbackInterface[] $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function addWarning(SchemaFeedbackInterface $warning): void
    {
        $this->warnings[] = $warning;
    }

    /**
     * @param SchemaFeedbackInterface[] $warnings
     */
    public function setWarnings(array $warnings): void
    {
        $this->warnings = $warnings;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getDeprecations(): array
    {
        return $this->deprecations;
    }

    public function addDeprecation(SchemaFeedbackInterface $deprecation): void
    {
        $this->deprecations[] = $deprecation;
    }

    /**
     * @param SchemaFeedbackInterface[] $deprecations
     */
    public function setDeprecations(array $deprecations): void
    {
        $this->deprecations = $deprecations;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getNotices(): array
    {
        return $this->notices;
    }

    public function addNotice(SchemaFeedbackInterface $notice): void
    {
        $this->notices[] = $notice;
    }

    /**
     * @param SchemaFeedbackInterface[] $notices
     */
    public function setNotices(array $notices): void
    {
        $this->notices = $notices;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function addLog(SchemaFeedbackInterface $log): void
    {
        $this->logs[] = $log;
    }

    /**
     * @param SchemaFeedbackInterface[] $logs
     */
    public function setLogs(array $logs): void
    {
        $this->logs = $logs;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getTraces(): array
    {
        return $this->traces;
    }

    public function addTrace(SchemaFeedbackInterface $trace): void
    {
        $this->traces[] = $trace;
    }

    /**
     * @param SchemaFeedbackInterface[] $traces
     */
    public function setTraces(array $traces): void
    {
        $this->traces = $traces;
    }
}
