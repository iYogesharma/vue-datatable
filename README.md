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
## Example(Element-UI)
```vue
  <template>
    <div class="app">
      <div class="filter-container">
        <el-input
          v-model="query.keyword"
          style="width: 200px;"
          class="filter-item"
          clearable
          @clear="resetKeyword"
          @keyup.enter.native="handleFilter"
        />
        <el-select
          v-model="query.filters[`users.role_id`]"
          clearable
          style="width: 90px"
          class="filter-item "
          @clear="resetFilters"
          @change="handleFilter"
        >
          <el-option
            v-for="role in roles"
            :key="role.id"
            :label="role.name"
            :value="role.id"
          />
        </el-select>
        <el-button
            class="filter-item"
            type="primary"
            icon="el-icon-search"
            @click="handleFilter"
          >
           search
          </el-button>
      </div>
      <el-table
        v-loading="loading"
        :data="data"
        border
        fit
        highlight-current-row
        style="width: 100%"
        @sort-change="sortList"
        >
          <el-table-column
            sortable="custom"
            prop="name"
            align="center"
            label="name"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.name }}</span>
            </template>
          </el-table-column>
          <el-table-column
            sortable
            align="center"
            prop="email"
           label="eamil"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.email }}</span>
            </template>
          </el-table-column>
          <el-table-column
            sortable
            align="center"
            prop="role"
           label="role"
          >
            <template slot-scope="scope">
              <span>{{ scope.row.role }}</span>
            </template>
          </el-table-column>
        </el-table>
     </div>
  </template>
  <script>
      export default {
        name: 'vue-datatable-test',
        data() {
          return {
            data: null,
            total: 0,
            roles: [
                {
                  id:1,
                  name: 'admin',
                },
                {
                  id:2,
                  name:'sub-admin'
                }
            ],
            query: {
             page: 1,
             limit: 10,
             keyword: '',
             order: {
               column: '',
               direction: '',
             },
             filters: {},
           }
          }
        },
        created () {
          this.getDataFromStorage();
        },
        methods: {
          async getDataFromStorage(){
            let self = this;
            await axios.get('/testUrl', {
              params: self.query
            })
            .then( res => {
              const { data, total } = res.data;
              self.data = data;
              self.total = total;
            })
          },
          /**
           * Handle tabel filter action
           * @param data value of current filter
           *
           * @return void
           */
          handleFilter(data) {
            if (data === '') {
              this.resetFilters();
            }
            this.query.page = 1;
            this.getDataFromStorage();
          },
        
          /**
           * Reset query filters to initial values
           * @return {void}
           */
          resetFilters() {
            this.query.filters = {};
          },
        
          /**
           * Reset query search keyword to initial value
           * @return {void}
           */
          resetKeyword() {
            this.query.keyword = '';
            this.getDataFromStorage();
          },
        
          /**
           * Handle sort action of table
           * @param {object} data sort details
           *
           * @return {void}
           */
          sortList(data) {
            const { prop, order } = data;
            if (order){
              if (prop === 'index') {
                this.$refs['table'].data.sort(() => -1);
              } else {
                this.query.order['column'] = prop;
                this.query.order['direction'] = order;
                this.handleFilter();
              }
            }
          }
        }
        
      }

  </script>
```

In the example given above you can also use component "el-pagination".
<b>keys</b>  total, query.limit and query.page  can be used to create dynamic pagination.
## License

The MIT License (MIT). Please see [License File](https://github.com/iYogesharma/datatables/blob/master/LICENSE.md) for more information.
