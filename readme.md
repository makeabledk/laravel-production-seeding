
# Laravel Production Seeding

This package provides a handy way to work with production seeding in Laravel.

Consider you have database tables you want to keep in-sync across environments. 
For instance some configuration table where you need to ensure a fixed number of rows, but not necessarily overwrite the values. 

Laravel Production Seeding helps you achieve this in a styleful manner!


Inspired by Edward Coleridge Smith's Laracon talk on the subject: 
[How to avoid database migration hell (YouTube)](https://www.youtube.com/watch?v=lH-FLJ363-Q)

--

Makeable is web- and mobile app agency located in Aarhus, Denmark.

## Install

You can install this package via composer:

``` bash
composer require makeabledk/laravel-production-seeding
```

## Usage

Create a Laravel seeder as you normally would. Next implement the SyncStrategy, and apply the fixed rows onto your model from the run method.

Finally you can run the seeder as part of your deployment process to ensure all environments implements the same basic blueprint.


## Examples

### Syncing a configuration table

Sync a database configuration table with an array-blueprint. Note how we hardcode the service-id, but allow for separate keys.

```php
class ConfigSeeder extends Seeder
{
    use \Makeable\ProductionSeeding\SyncStrategy;
    
    public $rows = [
        [
            'key' => 'some_service_id',
            'value' => '123456' 
        ],
        [
            'key' => 'some_service_public_key',
        ],
        [
            'key' => 'some_service_private_key',
        ],
    ];
    
    public function run()
    {
        $this->apply($this->rows, Config::class, 'key');
    }
}

```

Results in
```
| id | key                      | value  |
|----|--------------------------|--------|
| 1  | some_service_id          | 123456 |
| 2  | some_service_public_key  | NULL   |
| 3  | some_service_private_key | NULL   |
```

### Syncing rows with an order

Sometimes you may want to have a fixed list of items and then apply a certain order in the database.
 
This can easily be achieved with a `AppendsSortOrder` trait. Re-arranging the array items will change the order value in the database, but leave the id's intact.

```php
class LanguageSeeder extends Seeder
{
    use \Makeable\ProductionSeeding\SyncStrategy,
        \Makeable\ProductionSeeding\AppendsSortOrder;
    
    protected $sortKey = 'order'; // default if omitted
    
    public $rows = [
        [
            'code' => 'en_GB',
            'name' => 'English (GB)',
        ],
        [
            'code' => 'en_US',
            'name' => 'English (US)',
        ],
        [
            'code' => 'da_DK',
            'name' => 'Danish',
        ],
    ];
    
    public function run()
    {
        $this->apply($this->rows, LanguagesModel::class, 'code');
    }
}

```
Results in...
```
| id | code  | name         | order |
|----|-------|--------------|-------|
| 1  | en_GB | English (GB) | 1     |
| 2  | en_US | English (US) | 2     |
| 3  | da_DK | Danish       | 3     |
```
You could then re-arrange the array and run the seeder again. Then you could have...
```
| id | code  | name         | order |
|----|-------|--------------|-------|
| 1  | en_GB | English (GB) | 2     |
| 2  | en_US | English (US) | 1     |
| 3  | da_DK | Danish       | 3     |
```


Pretty cool, right?

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

You can run the tests with:

```bash
composer test
```

## Contributing

We are happy to receive pull requests for additional functionality. Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Rasmus Christoffer Nielsen](https://github.com/rasmuscnielsen)
- [Edward Coleridge Smith: How to avoid database migration hell (YouTube)](https://www.youtube.com/watch?v=lH-FLJ363-Q)
- [All Contributors](../../contributors)

## License

Attribution-ShareAlike 4.0 International. Please see [License File](LICENSE.md) for more information.