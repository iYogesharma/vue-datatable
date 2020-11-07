<?php
    
    namespace YS\VueDatatable;

    use Illuminate\Support\Facades\Schema;
    use YS\VueDatatable\Exceptions\DatatableDriverNotFoundException;
    
    abstract class DataTable
    {
        /**
         * Query to fetch data from storage
         * @var mixed
         */
        protected $query;
    
        /**
         * Query result
         * @var collection
         */
        protected $result;
    
        /**
         * Holds DatatableRequest Instance
         * @var Request
         */
        protected $request;
    
        /**
         * Represent name of ordering column
         * @var string
         */
        protected $orderBy;
    
        /**
         * Sort Directions
         * @var array
         */
        protected $directions= [
            'ascending' =>'asc',
            'descending'=>'desc'
        ];
        
        /**
         * Total data fetched fom storage
         *
         * @var int
         */
        protected  $total;
    
        /**
         *  Direction of sort asc/desc
         *  @var string
         */
        protected $dir;
    
        /**
         * Columns for where conditions
         * @var array
         */
        protected $whereColumns = [];
    
        /**
         * Table constructor.
         */
        public function __construct()
        {
            $this->request = new Request();
        }
        
        /**
         * Initialize datatable
         * @param  object $source instance of one of
         * supported driver class
         *
         * @return object
         * @throws DatatableDriverNotFoundException
         */
        public static function make( $source, $json )
        {
            $drivers = config('datatable.drivers');
            
            foreach( $drivers as $k=>$v)
            {
                if($source instanceof $k){
            
                    return app($v)->datatable( $source, $json );
                }
            }
            throw new DatatableDriverNotFoundException("Data source  must be instance of one of the drivers specified in config");
        }
    
        /**
         * Initialize datatable
         * @param  object $source instance of one of
         * supported driver class
         * @param bool $json
         * @return object
         * @throws DatatableDriverNotFoundException
         */
        public function of( $source, $json )
        {
            return $this->make( $source, $json ) ;
        }
        
        /**
         * Initialize datatable using Eloquent Builder
         * @param $source
         * @param bool $json
         * @return array
         * @throws \App\Exceptions\IncorrectDataSourceException
         */
        public static function eloquent( $source, $json )
        {
            return (new Eloquent)->create( $source, $json );
        }
    
        /**
         * Initialize datatable using Collection
         * @param $source
         * @param bool $json
         * @return array
         * @throws \App\Exceptions\IncorrectDataSourceException
         */
        public static function collection( $source, $json )
        {
            return (new Collection)->create( $source, $json );
        }
    
        /**
         * Initialize datatable using Query Builder
         * @param $source
         * @param bool $json
         * @return array
         * @throws \App\Exceptions\IncorrectDataSourceException
         */
        public static function query( $source, $json  )
        {
            return (new QueryBuilder)->create( $source, $json );
        }
        
        /**
         * Initialize datatable
         * @param  object $source instance of one of
         * supported driver class
         * @param bool $json
         * @return array|Table
         */
        public function datatable($source, $json )
        {
            // Set properties of class and initialize datatable
            $this->boot($source, $json );
            return  $json  ? $this->response() : $this;
        }
    
        /**
         * Initialize datatable buy setting all its
         * properties to be used throughout the
         * initialization process
         * @param object $source
         *
         * @return void
         */
        protected function boot($source, $json )
        {
            /** Set properties of instance of class*/
            $this->setQuery($source);
    
            //Set properties of class used by datatable
            $this->setProperties();
            
            $json ? $this->setResult() : '' ;
            
        }
    
        /**
         * Set final result of query
         *
         * @return void
         */
        protected function setResult()
        {
            $this->result = $this->query->get();
        }
    
        /**
         * Set @properties $totaland of class
         *
         * @returrn void
         */
        protected function setTotalData()
        {
            $this->total =  $this->query->count();
        }
    
        /**
         * Set filterable conditions on query
         *
         * @return void
         */
        protected function setFilters()
        {
            $filters = $this->request->getFilters();
            $this->query = $this->query->where($filters['basic']);
            if( count($filters['array']) > 0 )
            {
                $this->setArrayFilters( $filters['array']);
            }
        
        }
    
        /**
         * set array filter conditions on query
         *
         * @param array $filters
         */
        protected function setArrayFilters( array $filters )
        {
            foreach($filters as $k => $v )
            {
                if( count($v) > 0) 
                {
                    if (strpos($k, '_at') !== false || strpos($k, 'date') !== false || strpos($k, 'time') !== false) 
                    {
                        $this->query = $this->query->whereBetween($k, $v);
                    }
                    else 
                    {
                        $this->query = $this->query->whereIn($k, $v);
                    }
                }
            }
        }
        
        /**
         * Set datatable properties
         *
         * @return void
         */
        protected function setProperties()
        {
            //checks if ordering in enabled in datatable or not
            if ( $this->request->isOrderable() ) {
                $this->orderBy = $this->request->getOrderableColumn();
                $this->dir = $this->request->getOrderDirection() ;
                $this->prepareQueryWithOffsetAndOrderBy();
            }
            else
            {
                $this->prepareQueryWithOffset();
            }
        }
    
        /**
         * Set column names which are displayed on datatables
         *
         * @return void
         */
        protected function setColumns()
        {
            if(  empty($this->query->columns)  || $this->query->columns[0] === '*' )
            {
                $this->query->columns = Schema::getColumnListing( $this->query->from );
                delete_keys($this->query->columns, config('datatable.skip'));
            }
            else
            {
                foreach ( $this->query->columns as $k => $c )
                {
                    if( strpos($c,'.*'))
                    {
                        unset($this->query->columns[$k]);
                        
                        $table = explode('.*',$c)[0];
                        
                        $columns = Schema::getColumnListing( $table );
                        
                        delete_keys($columns, config('datatable.skip'));
                        
                        array_walk($columns, function(&$value)use($table) { $value = "{$table}.{$value}"; } );
                        
                        $this->query->columns = array_merge(
                            $this->query->columns,
                            $columns
                        );
                    }
                }
            }
        }
    
        /**
         * Set column names for where conditions of query
         *
         * @return void
         */
        protected function setWhereColumns()
        {
            foreach ($this->query->columns as $c)
            {
                if (!strpos($c, '_id'))
                {
                    if (strpos($c, ' as '))
                    {
                        $this->whereColumns[] =  explode(' as ', $c)[0];
                    
                    }
                    else
                    {
                            $this->whereColumns[] = $c;
                    }
                }
            }
        }
    
        /**
         * Prepare query to fetch result from storage
         *
         * @return bool
         */
        protected function prepareQuery()
        {
            $this->setColumns();
            
            $this->checkIfQueryIsForSearchingPurpose();
            
            $this->setTotalData();
            
            return true;
        }
        
        /**
         * Checks whether the query is for search/filter operation of datatable
         * if query is for searching tan prepare search query
         *
         * @return void
         */
        protected function checkIfQueryIsForSearchingPurpose()
        {
            if( $this->request->isSearchable() )
            {
                $this->searchQuery();
            }
        }
        
        /**
         * Prepare result to return as response
         *
         * @return void
         */
        protected function prepareQueryWithOffsetAndOrderBy()
        {
            $this->query = $this->query->offset($this->request->getStart())
                         ->limit($this->request->getPerPage())
                         ->orderBy($this->orderBy,$this->directions[ $this->dir ]);
        }
    
        /**
         * Prepare result to return as response
         *
         * @return void
         */
        protected function prepareQueryWithOffset()
        {
            $this->query = $this->query->offset($this->request->getStart())
                ->limit($this->request->getPerPage());
        }
    
        /**
         * Handle datatable search operation
         *
         */
        protected function searchQuery()
        {
            //set columns that are searchable
            $this->setWhereColumns();
        
            if (!empty($this->whereColumns)) {
                $this->query = $this->condition($this->request->getSearchString(), $this->whereColumns);
            }
        }
    
        /**
         * Apply conditions on query
         * @param string $search
         * @param array $columns
         *
         * @return mixed
         */
        protected function condition($search, $columns)
        {
            return $this->query->where(function ($q) use ($search, $columns) {
                $q->where($columns[0], 'LIKE', "%{$search}%");
                return $this->nestedWheres($q);
            });
        }
    
        /**
         * Return all where conditions to be nested
         *
         * @param mixed $q
         *
         * @return \Illuminate\Database\Eloquent\Builder instance
         */
        protected function nestedWheres($q)
        {
            for ($i = 1; $i < count($this->whereColumns); $i++) {
                $q->orWhere($this->whereColumns[$i], 'LIKE', "%{$this->request->getSearchString()}%");
            }
            return $q;
        }
    
        /**
         * Response returned by class
         *
         * @return array
         */
        protected function response():array
        {
            return [
                    'data' => $this->result,
                    'total' =>$this->total,
            ];
        }
    
        /**
         * Response returned by class in json
         *
         * @return string
         */
        protected function toJson():string
        {
            return json_encode( $this->response() );
        }
        
    }
