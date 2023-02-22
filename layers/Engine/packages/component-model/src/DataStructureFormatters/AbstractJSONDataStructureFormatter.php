<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructureFormatters;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
use stdClass;

abstract class AbstractJSONDataStructureFormatter extends AbstractDataStructureFormatter
{
    public function getContentType(): string
    {
        return 'application/json';
    }

    /**
     * @param array<string,mixed> $data
     */
    public function getOutputContent(array &$data): string
    {
        return (string)json_encode($data);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data
     */
    public function getFormattedData(array $data): array
    {
        $dataoutputitems = App::getState('dataoutputitems');
        if (!in_array(DataOutputItems::DATABASES, $dataoutputitems)) {
            return parent::getFormattedData($data);
        }

        /**
         * Convert entries from SplObjectStorage to string
         */
        $data['databases'] = $this->getDatabasesOutput($data['databases']);
        return $data;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>|array<string,array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>> $databases
     */
    protected function getDatabasesOutput(array $databases): array
    {
        $outputDatabase = [];
        $dboutputmode = App::getState('dboutputmode');
        if ($dboutputmode === DatabasesOutputModes::SPLITBYDATABASES) {
            /**
             * Notifications can appear under "database" and
             * "userstatedatabase", showing different fields on each.
             */
            foreach ($databases as $databaseName => $database) {
                $this->addDatabaseOutput($database, $outputDatabase);
            }
        } elseif ($dboutputmode === DatabasesOutputModes::COMBINED) {
            $this->addDatabaseOutput($databases, $outputDatabase);
        }
        
        return $outputDatabase;
    }

    /**
     * The "databases" contains entries of type SplObjectStorage,
     * so these must be handled separately.
     *
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $database
     * @param array<string,array<string|int,stdClass<string,mixed>>> $outputDatabase
     */
    protected function addDatabaseOutput(array &$database, array &$outputDatabase): void
    {
        foreach ($database as $dbKey => $dbObjectIDStorage) {
            foreach ($dbObjectIDStorage as $dbObjectID => $dbObjectStorage) {
                $outputDatabase[$dbKey][$dbObjectID] ??= new stdClass;
                /** @var FieldInterface $field */
                foreach ($dbObjectStorage as $field) {
                    /** @var mixed $field */
                    $fieldValue = $dbObjectStorage[$field];
                    $outputDatabase[$dbKey][$dbObjectID]->{$field->getOutputKey()} = $fieldValue;
                }
            }
        }
    }
}
