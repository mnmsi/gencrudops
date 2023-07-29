
# Gencrudops

Create a Laravel Crud Operations




## Installation

Install package with composer & publish

```bash
  composer require mnmsi/gencrudops
  php artisan vendor:publish --provider="Mnmsi\GenCrudOps\GenCrudOpsServiceProvider"
```


## Gnerate CRUD Operations

Ex.:`php artisan make:crud nameOfYourCrud "column1:type, column2, ...."`

Sample Command: `php artisan make:crud product "title:string, description:text"`



## Run CRUD Operation

- Update Route File Ex. for `product` => `Route::resource('products', ProductsController::class);`
- Run migration command `php artisan migrate`
- Finally run your project.

