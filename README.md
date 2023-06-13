# api-nbp

## Task description:

Your task is to write an application in pure PHP (without the use of frameworks) that will use the NBP (National Bank of
Poland) API to download exchange rates. The application should enable saving the downloaded exchange rates to the
database and displaying them in the form of a table. In addition, the application should enable the conversion of a
given amount from a selected currency to another and saving the conversion results to the database.

API NBP: http://api.nbp.pl/

## Requirements:

* Create a new MySQL database and configure database connection.
* Write appropriate classes or methods that will be responsible for communication with the NBP API and downloading
  exchange rates.
* Save the downloaded exchange rates to the database.
* Create a class or function that will generate a table with exchange rates based on data from a database.
* Create a form that allows the user to enter an amount and select two currencies: the source currency and the target
  currency.
* Write appropriate classes or methods that will convert the given amount from one currency to another using data from
  the database.
* Save the results of currency conversions to the database along with information about the source, target and converted
  amounts.
* View a list of recent currency conversion results with information about the source, target and converted amounts. Use
  data from the database.
* Use an object-oriented approach in your code by applying good practices related to object-oriented programming in pure
  PHP.
* Take care of proper application security, such as input validation, error handling, etc.
* Pay attention to the aesthetics of work and code. Try to maintain readability, proper formatting and naming of
  variables.

# Commands

## Start project

```
docker-compose up -d --build
docker exec -it api-nbp_php_1 bash
composer install
composer dump-autoload
php migrations -m
```

## Stop project

```
docker-compose down
```