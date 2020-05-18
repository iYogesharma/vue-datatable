<?php
    use  YS\VueDatatable\DataTable;
    
    if(!function_exists('datatable')) {
        /**
         * Display a listing of resource
         * @param mixed $source
         * @param bool $json
         * @return mixed
         * @throws \App\Exceptions\DatatableDriverNotFoundException
         */
        function datatable( $source, $json = true ){
            
            return DataTable::make( $source, $json );
            
        }
    }
    
    if(!function_exists('eloquent')) {
        /**
         * Display a listing of resource
         * @param mixed $source
         * @param bool $json
         * @return mixed
         * @throws YS\VueDatatable\Exceptions\incorrectDataSourceException
         */
        function eloquent( $source, $json = true ){
            
            return DataTable::eloquent( $source, $json );
            
        }
    }
    
    if(!function_exists('query_builder')) {
        /**
         * Display a listing of resource
         * @param mixed $source
         * @param bool $json
         * @return mixed
         * @throws YS\VueDatatable\Exceptions\incorrectDataSourceException
         */
        function query_builder( $source, $json = true){
            
            return DataTable::query( $source, $json );
            
        }
    }
    
    if(!function_exists('collection')) {
        /**
         * Display a listing of resource
         * @param mixed $source
         * @param bool $json
         * @return mixed
         * @throws YS\VueDatatable\Exceptions\incorrectDataSourceException
         */
        function collection( $source, $json = true){
            
            return DataTable::collection( $source, $json );
            
        }
    }

    if(!function_exists('delete_key')) {
        /**
         * Convert string to custom heading case
         * @param $array reference to array
         * @param tring $value
         * @return void
         */
        function delete_key( &$array , string $value ){
            
            if (($key = array_search($value, $array)) !== false) {
                unset($array[$key]);
            }
            
        }
    }
    
    if(!function_exists('delete_keys')) {
        /**
         * Delete specific keys from array by value
         * @param $array reference to array
         * @param array $values
         * @return void
         */
        function delete_keys( &$array , array $values ){
            
            foreach($values as $value )
            {
                delete_key( $array , $value );
            }
        }
    }
