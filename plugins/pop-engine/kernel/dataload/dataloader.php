<?php
namespace PoP\Engine;

abstract class Dataloader
{
    public function __construct()
    {
        $dataloader_manager = Dataloader_Manager_Factory::getInstance();
        $dataloader_manager->add($this);
    }
    
    abstract public function getName();

    /**
     * Function to override
     */
    public function getDatabaseKey()
    {
        return '';
    }

    /**
     * Function to override
     */
    public function getFieldprocessor()
    {
        return null;
    }
    
    /**
     * Function to override
     */
    public function getDataquery()
    {
        return null;
    }
    

    /**
     * Function to override
     */
    public function executeGetData($ids)
    {
        return array();
    }

    final public function getDataitems($formatter, $resultset, $ids_data_fields = array())
    {
        $fieldprocessor_manager = FieldProcessor_Manager_Factory::getInstance();

        if ($fieldprocessor_name = $this->getFieldprocessor()) {
            $fieldprocessor = $fieldprocessor_manager->get($fieldprocessor_name);
        }

        // Iterate data, extract into final results
        $databaseitems = $dbobjectids = array();
        if (!empty($resultset)) {
            foreach ($resultset as $resultitem) {
                // Obtain the data-fields for that $id
                $id = $fieldprocessor->getId($resultitem);
                $data_fields = array(
                    'primary' => $ids_data_fields[$id] ?? array(),
                );
                $dbobjectids[] = $id;

                \PoP\CMS\HooksAPI_Factory::getInstance()->doAction(
                    'Dataloader:modifyDataFields',
                    array(&$data_fields),
                    $this
                );
                
                // Add to the dataitems
                foreach ($data_fields as $dbname => $db_data_fields) {
                    if ($db_data_fields) {
                        $databaseitems[$dbname] = $databaseitems[$dbname] ?? array();
                        $dataitem = $formatter->addToDataitems($databaseitems[$dbname], $id, $db_data_fields, $resultitem, $fieldprocessor);
                    }
                }
            }
        }
        
        return array(
            'dbobjectids' => $dbobjectids,
            'dbitems' => $databaseitems,
        );
    }
    

    /**
     * key: id
     * value: data-fields to fetch for that id
     */
    final public function getData($ids_data_fields = array())
    {
    
        // Get the ids
        $ids = array_keys($ids_data_fields);

        // Execute the query, get data to iterate
        return $this->executeGetData($ids);
    }
}
