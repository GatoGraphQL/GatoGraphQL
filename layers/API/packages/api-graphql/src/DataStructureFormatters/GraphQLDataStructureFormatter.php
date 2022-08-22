<?php

declare(strict_types=1);

namespace PoPAPI\GraphQLAPI\DataStructureFormatters;

use PoP\ComponentModel\Constants\Response;
use PoP\ComponentModel\Feedback\FeedbackCategories;
use PoP\ComponentModel\Feedback\FeedbackEntryManagerInterface;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use SplObjectStorage;

class GraphQLDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    private const ADDITIONAL_FEEDBACK = 'additionalFeedback';
    
    private ?FeedbackEntryManagerInterface $feedbackEntryService = null;
    
    final public function setFeedbackEntryManager(FeedbackEntryManagerInterface $feedbackEntryService): void
    {
        $this->feedbackEntryService = $feedbackEntryService;
    }
    final protected function getFeedbackEntryManager(): FeedbackEntryManagerInterface
    {
        return $this->feedbackEntryService ??= $this->instanceManager->getInstance(FeedbackEntryManagerInterface::class);
    }

    public function getName(): string
    {
        return 'graphql';
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data
     */
    public function getFormattedData(array $data): array
    {
        $ret = [];

        /**
         * Calculate the data first (even if printed later)
         * as it can also add errors.
         */
        $resultData = parent::getFormattedData($data);
        
        /**
         * If the formatting produced additional feedback entries,
         * transfer them to the $data object.
         */
        if (isset($resultData[self::ADDITIONAL_FEEDBACK])) {
            foreach ($resultData[self::ADDITIONAL_FEEDBACK][Response::GENERAL_FEEDBACK] ?? [] as $category => $feedbackEntries) {
                $data[Response::GENERAL_FEEDBACK][$category] = array_merge(
                    $data[Response::GENERAL_FEEDBACK][$category] ?? [],
                    $feedbackEntries
                );
            }
            foreach ($resultData[self::ADDITIONAL_FEEDBACK][Response::DOCUMENT_FEEDBACK] ?? [] as $category => $feedbackEntries) {
                $data[Response::DOCUMENT_FEEDBACK][$category] = array_merge(
                    $data[Response::DOCUMENT_FEEDBACK][$category] ?? [],
                    $feedbackEntries
                );
            }
            foreach ($resultData[self::ADDITIONAL_FEEDBACK][Response::SCHEMA_FEEDBACK] ?? [] as $category => $feedbackEntries) {
                $data[Response::SCHEMA_FEEDBACK][$category] = array_merge(
                    $data[Response::SCHEMA_FEEDBACK][$category] ?? [],
                    $feedbackEntries
                );
            }
            foreach ($resultData[self::ADDITIONAL_FEEDBACK][Response::OBJECT_FEEDBACK] ?? [] as $category => $feedbackEntries) {
                $data[Response::OBJECT_FEEDBACK][$category] = array_merge(
                    $data[Response::OBJECT_FEEDBACK][$category] ?? [],
                    $feedbackEntries
                );
            }
            unset($resultData[self::ADDITIONAL_FEEDBACK]);
        }

        $this->maybeAddTopLevelExtensionsEntryToResponse($ret, $data);

        /**
         * Print the feedback at the top
         */
        $errors = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR] ?? []),
        );
        if ($errors !== []) {
            $ret['errors'] = $errors;
        }

        if ($resultData) {
            $ret['data'] = $resultData;
        }

        return $ret;
    }

    /**
     * "warnings", "deprecations" and "logs" are top-level entries:
     * since they are not part of the spec, place them under
     * the top-level entry "extensions":
     *
     * > This entry is reserved for implementors to extend the protocol however they see fit,
     * > and hence there are no additional restrictions on its contents.
     *
     * @see http://spec.graphql.org/June2018/#sec-Response-Format
     * @param array<string,mixed> $ret
     * @param array<string,mixed> $data
     */
    protected function maybeAddTopLevelExtensionsEntryToResponse(array &$ret, array $data): void
    {
        if (!$this->addTopLevelExtensionsEntryToResponse()) {
            return;
        }

        // Deprecations
        $deprecations = array_merge(
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::DEPRECATION] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::DEPRECATION] ?? []),
        );
        if ($deprecations !== []) {
            $ret['extensions']['deprecations'] = $deprecations;
        }

        // Warnings
        $warnings = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::WARNING] ?? []),
        );
        if ($warnings !== []) {
            $ret['extensions']['warnings'] = $warnings;
        }

        // Logs
        $logs = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::LOG] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::LOG] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::LOG] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::LOG] ?? []),
        );
        if ($logs !== []) {
            $ret['extensions']['logs'] = $logs;
        }

        // Notices
        $notices = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
        );
        if ($notices !== []) {
            $ret['extensions']['notices'] = $notices;
        }

        // Suggestions
        $suggestions = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
        );
        if ($suggestions !== []) {
            $ret['extensions']['suggestions'] = $suggestions;
        }
    }

    /**
     * Indicate if to add entry "extensions" as a top-level entry
     */
    protected function addTopLevelExtensionsEntryToResponse(): bool
    {
        return true;
    }

    /**
     * @return array<int,mixed[]>
     * @param array<array<string,mixed>> $entries
     */
    protected function reformatGeneralEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $item) {
            $ret[] = $this->getGeneralEntry($item);
        }
        return $ret;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getGeneralEntry(array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($extensions = $item[Tokens::EXTENSIONS]) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    /**
     * @return array<int,mixed[]>
     * @param array<array<string,mixed>> $entries
     */
    protected function reformatDocumentEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $item) {
            $ret[] = $this->getDocumentEntry($item);
        }
        return $ret;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getDocumentEntry(array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($locations = $item[Tokens::LOCATIONS]) {
            $entry['locations'] = $locations;
        }
        if (
            $extensions = array_merge(
                $this->getDocumentEntryExtensions($item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $item
     */
    protected function getDocumentEntryExtensions(array $item): array
    {
        $extensions = [];
        if ($path = $item[Tokens::PATH] ?? null) {
            $extensions['path'] = $path;
        }
        return $extensions;
    }

    /**
     * @param array<string,SplObjectStorage<FieldInterface,array<string,mixed>>> $entries
     * @return array<int,mixed[]>
     */
    protected function reformatSchemaEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $typeOutputKey => $storage) {
            foreach ($storage as $field) {
                /** @var FieldInterface $field */
                $items = $storage[$field];
                /** @var array<string,mixed> $items */
                foreach ($items as $item) {
                    $ret[] = $this->getSchemaEntry($typeOutputKey, $item);
                }
            }
        }
        return $ret;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getSchemaEntry(string $typeOutputKey, array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($locations = $item[Tokens::LOCATIONS]) {
            $entry['locations'] = $locations;
        }
        /**
         * Add the causes of the error, if any.
         *
         * @see https://github.com/graphql/graphql-spec/issues/893
         */
        if ($causes = $item[Tokens::CAUSES] ?? []) {
            $entry['causes'] = $causes;
        }
        if (
            $extensions = array_merge(
                $this->getSchemaEntryExtensions($typeOutputKey, $item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getSchemaEntryExtensions(string $typeOutputKey, array $item): array
    {
        $extensions = [];
        if ($path = $item[Tokens::PATH] ?? null) {
            $extensions['path'] = $path;
        }
        $extensions['type'] = $typeOutputKey;
        if ($field = $item[Tokens::FIELD] ?? null) {
            $extensions['field'] = $field;
        } elseif ($dynamicField = $item[Tokens::DYNAMIC_FIELD] ?? null) {
            $extensions['dynamicField'] = $dynamicField;
        }
        return $extensions;
    }

    /**
     * @param array<string,SplObjectStorage<FieldInterface,array<array<string,mixed>>>> $entries
     * @return array<int,mixed[]>
     */
    protected function reformatObjectEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $typeOutputKey => $fieldItems) {
            /** @var FieldInterface $field */
            foreach ($fieldItems as $field) {
                /** @var array<string,mixed> */
                $items = $fieldItems[$field];
                foreach ($items as $item) {
                    $ret[] = $this->getObjectEntry($typeOutputKey, $item);
                }
            }
        }
        return $ret;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getObjectEntry(string $typeOutputKey, array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($locations = $item[Tokens::LOCATIONS]) {
            $entry['locations'] = $locations;
        }
        /**
         * Add the causes of the error, if any.
         *
         * @see https://github.com/graphql/graphql-spec/issues/893
         */
        if ($causes = $item[Tokens::CAUSES] ?? []) {
            $entry['causes'] = $causes;
        }
        if (
            $extensions = array_merge(
                $this->getObjectEntryExtensions($typeOutputKey, $item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    /**
     * The entry is similar to Schema, plus the
     * addition of the object ID/IDs
     */
    protected function getObjectEntryExtensions(string $typeOutputKey, array $item): array
    {
        $extensions = $this->getSchemaEntryExtensions($typeOutputKey, $item);

        /** @var array<string|int> */
        $ids = $item[Tokens::IDS];
        if (count($ids) === 1) {
            $extensions['id'] = $ids[0];
        } else {
            $extensions['ids'] = $ids;
        }

        return $extensions;
    }

    /**
     * @param FieldInterface[] $previouslyResolvedFieldsForObject
     * @param array<string,mixed> $sourceRet
     * @param array<string,mixed>|null $resolvedObjectRet
     * @param SplObjectStorage<FieldInterface,mixed> $resolvedObject
     */
    protected function resolveObjectData(
        array $previouslyResolvedFieldsForObject,
        LeafField $leafField,
        string $typeOutputKey,
        array &$sourceRet,
        ?array &$resolvedObjectRet,
        SplObjectStorage $resolvedObject,
        string|int $objectID,
    ): void {
        /**
         * Validate Field Selection Merging: 2 different fields
         * cannot have the same name/alias on the same block in
         * the response.
         *
         * @see https://spec.graphql.org/draft/#sec-Field-Selection-Merging
         */
        $isError = false;
        if (array_key_exists($leafField->getOutputKey(), $resolvedObjectRet)) {
            /**
             * Check that the original field is indeed different to this one.
             * To find out, search for the previous fields with the same
             * outputKey but different query string (hence they are different)
             */
            $differentFieldsWithSameOutputKeyForObject = array_filter(
                $previouslyResolvedFieldsForObject,
                fn (FieldInterface $field) => $field->getOutputKey() === $leafField->getOutputKey() && $field->asQueryString() !== $leafField->asQueryString()
            );
            $isError = $differentFieldsWithSameOutputKeyForObject !== [];
        }
        if ($isError) {
            /**
             * Set response to null
             */
            $resolvedObjectRet[$leafField->getOutputKey()] = null;

            /**
             * Add an entry on the "errors" section
             */
            $item = $this->getFeedbackEntryManager()->formatObjectOrSchemaFeedbackCommonEntry(
                $leafField,
                $leafField->getLocation(),
                [],
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_3_2,
                    [
                        $leafField->asFieldOutputQueryString(),
                        $objectID,
                        $leafField->getOutputKey()
                    ]
                ),
                [$objectID],
            );

            /** @var SplObjectStorage<FieldInterface,array<string,mixed>> */
            $typeFeedbackEntries = $sourceRet[self::ADDITIONAL_FEEDBACK][Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR][$typeOutputKey] ?? new SplObjectStorage();
            /** @var array<string,mixed> */
            $fieldTypeFeedbackEntries = $typeFeedbackEntries[$leafField] ?? [];
            $fieldTypeFeedbackEntries[] = $item;
            $typeFeedbackEntries[$leafField] = $fieldTypeFeedbackEntries;
            $sourceRet[self::ADDITIONAL_FEEDBACK][Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR][$typeOutputKey] = $typeFeedbackEntries;
            return;
        }
        parent::resolveObjectData(
            $previouslyResolvedFieldsForObject,
            $leafField,
            $typeOutputKey,
            $sourceRet,
            $resolvedObjectRet,
            $resolvedObject,
            $objectID,
        );
    }
}
