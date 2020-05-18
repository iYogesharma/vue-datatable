<?php

namespace YS\VueDatatable\Tests;

use YS\VueDatatable\Tests\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->migrateDatabase();

        $this->seedDatabase();
    
        $this->request();
    }

    protected function migrateDatabase()
    {
        /** @var \Illuminate\Database\Schema\Builder $schemaBuilder */
        $schemaBuilder = $this->app['db']->connection()->getSchemaBuilder();
        if (! $schemaBuilder->hasTable('users')) {
            $schemaBuilder->create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('email');
                $table->integer('role_id');
                $table->timestamps();
            });
        }

    }

    protected function seedDatabase()
    {

        collect(range(1, 20))->each(function ($i)  {
            /** @var User $user */
            $user = User::query()->create([
                'name'  => 'Record-' . $i,
                'email' => 'Email-' . $i . '@example.com',
                'role_id'=>1,
            ]);
        });
    }
    
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', true);
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
           \YS\VueDatatable\DataTableServiceProvider::class,
        ];
    }

    protected function  request()
    {
        $request = app('request');
        $request->merge([
            "page" => "1",
            "limit" => "10",
            "keyword" => "test",
            "order" => '{"column":"","direction":""}',
            "filters" => '{"users.role_id":1}',
        ]);
    }
}