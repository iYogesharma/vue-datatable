<?php
    
    namespace YS\VueDatatable;
    
    use Illuminate\Database\Query\Builder;
    use YS\VueDatatable\Exceptions\IncorrectDataSourceException;
    
    class QueryBuilder extends DataTable
    {
        /**
         * Initializes datatable using Query Builder
         * @param $source
         * @param bool $json
         * @return array|mixed|string
         * @throws IncorrectDataSourceException
         */
        public function create( $source, $json )
        {
            if ($source instanceof Builder) {
                return $this->datatable( $source, $json );
            }
            
            throw new IncorrectDataSourceException("Data source  must be instance of \Illuminate\Database\Query\Builder");
            
        }
        
        /**
         * Set @property $query of class
         * @param  Builder $source
         *
         * @return void
         */
        public function setQuery($source)
        {
            $this->query = $source;
    
            if( $this->request->hasFilters())
            {
                $this->setFilters();
            }
            
            $this->prepareQuery();
        }
    }
