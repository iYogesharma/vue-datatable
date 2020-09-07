<?php

    namespace  YS\VueDatatable;

    class Request
    {
        protected $order = [];
        
        public function __construct()
        {
            $this->request = app('request');
        }
        
        /**
         * Check if Datatable is orderable.
         *
         * @return bool
         */
        public function isOrderable()
        {
            $this->order = json_decode( $this->request->input('order'),true);
            
            return  $this->order['column'] !='' && count($this->order) > 0;
        }
    
        /**
         * Check if Datatable is searchable.
         *
         * @return bool
         */
        public function isSearchable()
        {
            return $this->request->input('keyword') != '';
        }
    
        /**
         * @return string
         */
        public function getDraw()
        {
            return $this->request->input('page');
        }
    
        /**
         * @return string
         */
        public function getStart()
        {
            return ($this->request->input('page')-1 )* $this->getPerPage() ;
        }
    
        /**
         * Get max data per page for pagination
         * @return array|string
         */
        public function getPerPage()
        {
            return intval($this->request->input('limit'));
        }
        
        /**
         * String to search in datatable
         * @return string
         */
        public function getSearchString()
        {
            return $this->request->input('keyword');
        }
    
        /**
         * Index of order by column of datatable
         * @return string
         */
        public function getOrderableColumn()
        {
            return $this->order['column'];
        }
    
        /**
         * Ordering direction of order by column
         * @return string
         */
        public function getOrderDirection()
        {
            return $this->order['direction'];
        }
    
        /**
         * String to search in datatable
         * @return string
         */
        public function hasFilters()
        {
            return $this->request->input('filters')!=='';
        }
    
        /**
         * Filters to be applied on table query
         * @return array
         */
        public function getFilters(){
            $filters =  json_decode($this->request->input('filters'),true) ;
            $arrayFilters = [];
            foreach($filters as $k=>$v) {
                if(gettype($v) === 'array') {
                    $arrayFilters[$k] = $v;
                    unset($filters[$k]);
                }
            }
    
            return [ 'basic' => $filters, 'array' => $arrayFilters ];
        }
    }
