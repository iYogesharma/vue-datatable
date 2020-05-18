<?php
    
    namespace YS\VueDatatable\Tests;
    
    use YS\VueDatatable\DataTable;
    
    use YS\VueDatatable\Tests\Models\User;
    
    use DB;
use YS\VueDatatable\Eloquent;

class DataTableResultTest extends TestCase
    {
    
        public function test_it_should_returns_array()
        {
            $data = datatable(User::select('id','name'));
            
            $this->assertTrue( gettype($data) === "array");
        }
        
    }
    