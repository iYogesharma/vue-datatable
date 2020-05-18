<?php
    
    
    namespace YS\VueDatatable;
    
    use YS\VueDatatable\Exceptions\IncorrectDataSourceException;
    
    class Collection extends DataTable
    {
        /**
         * Initializes datatable using collection
         * @param $source
         * @param bool $json
         * @return array|mixed|string
         * @throws IncorrectDataSourceException
         */
        public function create($source, $json )
        {
            if (
                $source instanceof \Illuminate\Database\Eloquent\Collection
                || $source instanceof \Illuminate\Support\Collection
            ) {
                return $this->datatable( $source, $json );
            }
            
            throw new IncorrectDataSourceException(
                "Data source  must be instance of either \Illuminate\Database\Eloquent\Collection  or \Illuminate\Supprt\Collection"
            );
            
        }
        
        /**
         * Set @param \Illuminate\Database\Eloquent\Collection|\Illuminate\Supprt\Collection| $source
         *
         * @return void
         * @property $query of class
         */
        public function setQuery($source)
        {
            $this->query = $source;
    
            if( $this->request->hasFilters())
            {
                $this->setFilters();
            }
            $this->setTotalData();
            
            $this->setResult();
        }
        
        /**
         * Set filterable conditions on query
         *
         * @return void
         */
        protected function setFilters()
        {
            foreach($this->request->getFilters() as $attr=>$filter )
            {
                $this->query->where( $attr, $filter );
            }
        }

         /**
         * Set datatable properties
         *
         * @return void
         */
        protected function setProperties()
        {
           //
        }
        

        public function setResult()
        {
            $this->result = $this->query;
        }
    
    }
