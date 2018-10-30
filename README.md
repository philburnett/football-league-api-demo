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

Authorization Header - You can use this to access the API.
```
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwicm9sZXMiOlsiUk9MRV9VU0VSIl0sImlhdCI6MTUxNjIzOTAyMn0._O7zDzGeK1PAhyWTU2t6XIZmMbQ0F-7ukg-6ysZp7ig
```

## Notes
 - No behat (no time).  I've Included controller integration tests instead.
 - Doctrine is causing a depreciation error when running unit tests.  Not sure there is a solution for this yet.
 - JWT are not signed, I would do this in a prod env.
 - Although correct status codes are being sent, full errors are showing for development. 