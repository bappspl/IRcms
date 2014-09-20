[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/olliebrennan/Zf2SlugGenerator/badges/quality-score.png?s=653d247434c5bd09c553f9a41340cc5dc23517de)](https://scrutinizer-ci.com/g/olliebrennan/Zf2SlugGenerator/)
[![Build Status](https://travis-ci.org/olliebrennan/Zf2SlugGenerator.png?branch=master)](https://travis-ci.org/olliebrennan/Zf2SlugGenerator)

Zf2SlugGenerator
====================

A simple module that creates slugs.

If required, the slug generator ties into the DB adapter if required.

Installation
--------------
1) Add the following requirement to your projects composer.json file.

Within the "repositories" section (create it as below if it doesn't exist):

Within the "require" section:

```php
"olliebrennan/zf2-slug-generator": "dev-master"
```

2) Open up your command line and run

```
php ./composer.phar update
```

2) Add 'Zf2SlugGenerator' to your /config/application.config.php modules

Usage Example
--------------

Creating a basic slug
```
$service = $this->getServiceLocator()->get('Zf2SlugGenerator\SlugService');
$slug = $service->create('My String To Slug', false);
```

Calling a slug using your existing DB adapter
```
$service = $this->getServiceLocator()->get('Zf2SlugGenerator\SlugService')
    ->setDbTableName('tableName')
    ->setDbColumnName('columnName');
$slug = $service->create('My String To Slug');
```
