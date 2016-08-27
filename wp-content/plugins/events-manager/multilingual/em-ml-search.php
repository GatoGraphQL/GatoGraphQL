<?php
class EM_ML_Search {
    
    public static function init(){
        add_filter('em_object_get_default_search','EM_ML_Search::em_object_get_default_search',10,3);
    }
    
    public static function em_object_get_default_search( $defaults, $array, $super_defaults ){
        if( !empty($defaults['location']) ){
            //check that this location ID is the original one, given that all events of any language will refer to the location_id of the original
            $EM_Location = em_get_location($defaults['location']);
            if( !EM_ML::is_original($EM_Location) ){
                $defaults['location'] = EM_ML::get_original_location($EM_Location)->location_id;
            }
        }
        return $defaults;
    }
    
}
EM_ML_Search::init();