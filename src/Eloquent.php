<?php
    
    
    namespace YS\VueDatatable;
    
    use Illuminate\Database\Eloquent\Builder;
    use YS\VueDatatable\Exceptions\IncorrectDataSourceException;
    
    class Eloquent extends DataTable
    {
        /**
         * Initializes datatable using eloquent
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
            
            throw new IncorrectDataSourceException("Data source  must be instance \Illuminate\Database\Eloquent\Builder");
            
        }
        
        /**
         * Set @param Builder $source
         *
         * @return void
         * @property $query of class
         */
        public function setQuery( $source )
        {
            $this->query = $source->getQuery();
    
            if( $this->request->hasFilters())
            {
                $this->setFilters();
            }
            
            $this->prepareQuery();
        }
    }
