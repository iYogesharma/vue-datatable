<?php

namespace YS\VueDatatable\Tests;

use YS\VueDatatable\DataTable;
use YS\VueDatatable\QueryBuilder;
use YS\VueDatatable\Collection;
use YS\VueDatatable\Eloquent;

use YS\VueDatatable\Tests\Models\User;

use DB;

class DataTableInitTest extends TestCase
{

    public function test_datatable_can_be_initialized_from_query_builder()
    {
        $this->withoutExceptionHandling();

        $table = datatable(User::getQuery(),false);
        
        $this->assertTrue( $table instanceof QueryBuilder );
    }

    public function test_datatable_can_be_initialized_from_eloquent_collection()
    {
        $this->withoutExceptionHandling();

        $table = datatable(User::get(),false);
      
        $this->assertTrue( $table instanceof Collection );
    }

    public function test_datatable_can_be_initialized_from_db_collection()
    {
        $this->withoutExceptionHandling();

        $table = datatable(DB::table('users')->get(),false);
      
        $this->assertTrue( $table instanceof Collection );
    }

    public function test_datatable_can_be_initialized_from_eloquent()
    {
        $this->withoutExceptionHandling();

        $table = datatable(user::select('id','name','email'),false);
        $this->assertTrue( $table instanceof Eloquent );
    }
    
}
