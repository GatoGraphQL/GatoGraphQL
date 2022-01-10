<?php

declare(strict_types=1);

namespace PoP\Engine\DataStructureFormatters;

use PoP\Root\App;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\DataStructure\AbstractJSONDataStructureFormatter;

class DBItemListDataStructureFormatter extends AbstractJSONDataStructureFormatter
{
    public function getName(): string
    {
        return 'dbitemlist';
    }

    protected function getJsonEncodeType(): ?int
    {
        return JSON_FORCE_OBJECT;
    }

    protected function addDBEntries(&$database, &$merged_databases): void
    {
        foreach ($database as $db_key => $dbobject) {
            foreach ($dbobject as $dbobject_id => $dbobject_data) {
                $merged_databases[$db_key][$dbobject_id] = array_merge(
                    $merged_databases[$db_key][$dbobject_id] ?? array(),
                    $dbobject_data
                );
            }
        }
    }

    public function getFormattedData($data)
    {
        // If we are requesting only the databases, then return these as a list of items
        $dataoutputitems = App::getState('dataoutputitems');
        if (in_array(DataOutputItems::DATABASES, $dataoutputitems)) {
            $ret = array();

            // If there are no "databases" entry, then there are no results, so return an empty array
            if ($databases = $data['dbData']) {
                // First pass: merge all content about the same DB object
                // Eg: notifications can appear under "database" and "userstatedatabase", showing different fields on each
                $merged_databases = array();
                $dboutputmode = App::getState('dboutputmode');
                if ($dboutputmode == DatabasesOutputModes::SPLITBYDATABASES) {
                    foreach ($databases as $database_name => $database) {
                        $this->addDBEntries($database, $merged_databases);
                    }
                } elseif ($dboutputmode == DatabasesOutputModes::COMBINED) {
                    $this->addDBEntries($databases, $merged_databases);
                }

                // Second pass: extract all items, and return it as a list
                // Watch out! It doesn't make sense to mix items from 2 or more db_keys (eg: posts and users),
                // so this formatter should be used only when displaying data from a unique one (eg: only posts)
                foreach ($merged_databases as $db_key => $dbobject) {
                    $ret = array_merge(
                        $ret,
                        array_values($dbobject)
                    );
                }
            }

            return $ret;
        }

        return parent::getFormattedData($data);
    }
}
