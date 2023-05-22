
# Links Importer
(Known as The Lord Of the Links)

A service for import csv files using Symfony

## Features

- Import csv file
- Check URL existence

## Installation

setup the project using docker

```bash
git clone
docker-compose build
docker-compose up -d
exec to php container
php bin/console composer install
php bin/console make:migration
php bin/console doctrine:migration:migrate
php bin/console messenger:consume -vv
```


## API Reference

#### Import API

```http
  GET http://127.0.0.1:8080/link/csv/import
```


#### PHP My Admin

```http
  GET http://localhost:8081
```

#### RabbitMQ

```http
  GET http://127.0.0.1:5672/

```

#### Redis commander

```http
  GET http://127.0.0.1:8060/

```

## Optimizations

- Using async Symfony messenger along side with RabbitMQ make the persist
process easier for the system, cleaning up unneeded variables was somthing
useful for the RAM, and not to forget about:
```http
$em->clear()
```

- Using multiple tables to save the links' means smaller indexing and faster look up,
  also less space-intensive and lends better to getting better data out of it.
  

## Important notices

#### Empty DB
In case checking existence of URLs in the same CSV file is important and not inserted in the DB before:

Trying this service with randomly generated URLs from some website 
with an empty DB will cause less accuracy in checking URLs existence
and that because of Messenger way of work in local environment
so please comment the `3 dispatch` calls in `save` method in `UrlService` class and uncomment the
`saveAll() saveDomainPathAndQuery() saveQuery()` methods.

After, populating the DB you can confirm the performance by re-importing the same CSV by using Messenger.

`saveAll() saveDomainPathAndQuery() saveQuery()` methods are meant to be temb functions and they are not
follow the architect applied.

**Architecture:**

Layers: Controller >> Service >> Manager >> Repository

Sub: Request - Response - Const - Message - Message handler

## Upcoming upgrades

- Clean up code.
- Refactor checker method.  
- Add tests.
- Care about www prefix.
- Care about subdomains.
- Decrease queries used.
- Handle messages in batches.
- Clean up variable uses.

### CSV Importer bundle

- ["thephpleague"](https://github.com/thephpleague/csv)
 
