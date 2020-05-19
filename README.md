[![Latest Stable Version](https://poser.pugx.org/iyogesharma/vue-datatable/v)](//packagist.org/packages/iyogesharma/vue-datatable)
[![Latest Unstable Version](https://poser.pugx.org/iyogesharma/vue-datatable/v/unstable)](//packagist.org/packages/iyogesharma/vue-datatable)
[![Total Downloads](https://poser.pugx.org/iyogesharma/vue-datatable/downloads)](//packagist.org/packages/iyogesharma/vue-datatable)
[![Daily Downloads](https://poser.pugx.org/iyogesharma/vue-datatable/d/daily)](//packagist.org/packages/iyogesharma/vue-datatable)
[![License](https://poser.pugx.org/iyogesharma/vue-datatable/license)](//packagist.org/packages/iyogesharma/vue-datatable)
# Vue DataTable For Laravel 
A simple package to ease DataTable server side handling

This package is created to handle server-side rendring of DataTables by using Eloquent ORM, Query Builder or Collection.This package helps you to easily manage server side rendring of datatables if you are dealing with js libraries like [Vue](https://vuejs.org/) or [React](https://reactjs.org/). Currently [Element-UI](https://element.eleme.io/) is completely supported by this package. Soon some other populer libraries will also get supported.



## Using Helper Function

```php
    return datatables(User::query());
    return datatables(DB::table('users')->join1()->join2()->select(column1,column2,...columnK));
    return datatables(DB::table('users')->get());
    return datatables(User::all());
```

function datatable also contain optional parameter $json with default value to true, set this param to false if you 
want to use instance of DataTable particular database driver class. 
eg, below code return instance of <b>YS\vueDataTable\Eloquent class</b>

```php
    return datatables(User::query(),false);

```

vue-datatables also includes some other helper funcions that you can use if you want to use a particular database driver

```php

    return eloquent(User::query());
    return query_bulder(DB::table('users')->join1()->join2()->select(column1,column2,...columnK));
    return collection(DB::table('users')->get());
    return collection(User::all());
```

## Input Format

```javascript

  let query = {
          page: 1,
          limit: 10,
          keyword: '',
          order: {
            column: '',
            direction: '',
          },
          filters: {"users.role_id":2},
        };

``` 
You must send query object given above in the query parameter in order to use this package.
<ul>
  <li> <b>page</b> represent page number in pagination </li>
  <li> <b>limt</b> number of records be displayed on a single page </li>
  <li> <b>keyword</b> key you want to search in table </li>
  <li> <b>keyword</b> string you want to search in table </li>
  <li> <b>order</b> handle ordering of columns in table. here key column represents name of ordering column  and key direction represents direction. key direction can only have values ascending or descending.</li>
  <li> <b>filters</b> filters table data eg, role_id if you want to see users of a particular role only.</li>
</ul>

## Requirements
- [PHP >= 7.0](http://php.net/)
- [Laravel 5.*|6.*|7.*](https://github.com/laravel/framework)

## Quick Installation
```bash
$ composer require iyogesharma/vue-datatable:"1.*"
```

#### Service Provider & Facade (Optional on Laravel 5.5)
Register provider and facade on your `config/app.php` file.
```php
'providers' => [
    ...,
   YS\VueDatatable\DataTableServiceProvider::class,
]

```

## License

The MIT License (MIT). Please see [License File](https://github.com/iYogesharma/datatables/blob/master/LICENSE.md) for more information.
