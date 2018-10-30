# Simple Football League API

## Installation

Clone project repository:

```
git clone https://github.com/philburnett/football-league-api-demo.git
```

Install composer dependencies (requires composer on path):

```
composer install
```

Setup / Reset the database
```
./reset-db.sh
```
Run Tests

```
php bin/phpunit
```

Start the server
```
php bin/console server:start
```

Stop the server
```
php bin/console server:stop
```

## Notes
 - No behat (no time).  I've Included controller integration tests instead.
 - Doctrine is causing a depreciation error when running unit tests.  Not sure there is a solution for this yet.