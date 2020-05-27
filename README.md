<a href="https://infifnisoftware.ro" target="_blank">
    <img src="https://infifnisoftware.ro/themes/custom/infifni/logo.svg" alt="infifni logo" height="200" />
</a>
<h1>
    Fan Courier API Client Library
    <br />
    License MIT
</h1>

## FanCourier API Client

A client developed as a library, easy to integrate in other projects.

## Installation

Install the package through [Composer](http://getcomposer.org/). 

Run the Composer require command from the Terminal:

    composer require infifni/fan-courier-api-client

Now you're ready to start using the FanCourier API Client in your application.

## Overview
Look at one of the following topics to learn more about FanCourier API Client.

* [Usage](#usage)
* [Exceptions](#exceptions)

## Usage
Explanations
```php
// default values for testing purposes
$clientId = '7032158';
$username = 'clienttest';
$password = 'testing';

// for all the methods available you can see the request params
// in the associated class
// e.g.
use Infifni\FanCourierApiClient\Client;
(new Client($clientId, $username, $password))->city();
// now check \Infifni\FanCourierApiClient\Request\City::getFieldRules
// to understand what params are required and which are optional
```
See the phpunit tests for more in depth understanding of how the library works.
The FanCourier API Client library gives you the following methods to use:

### city method

Retrieves cities based on county and language.

**The `city()` method will return an array of objects with: judet, localitate, agentie, km, cod_rutare, id_localitate_fan.
These keys will be translated if english language is being specified.**

```php
// examples
use Infifni\FanCourierApiClient\Client;
use Infifni\FanCourierApiClient\Request\City;

// to fetch specific county
(new Client('7032158', 'clienttest', 'testing'))
    ->city([
        'judet' => 'Constanta', // optional
        'language' => City::LANGUAGE_RO_ALLOWED_VALUE // optional
    ]);

// or to fetch all cities
(new Client('7032158', 'clienttest', 'testing'))
    ->city();
```

### streets method

Retrieves streets based on county, city and language.

**The `streets()` method will return an array of objects with the following keys: judet, localitate, strada, de_la, pana_la, paritate, cod_postal, tip, cod_cartare, numar_depozite.
These keys will be translated if english language is being specified.**

```php
use Infifni\FanCourierApiClient\Client;
use Infifni\FanCourierApiClient\Request\Streets;

// To fetch specific county
(new Client('7032158', 'clienttest', 'testing'))
    ->streets([
        'judet' => 'Bucuresti', // optional
        'localitate' => 'Bucuresti', // optional
        'language' => Streets::LANGUAGE_RO_ALLOWED_VALUE // optional
    ]);
// or to fetch all streets from Romania
(new Client('7032158', 'clienttest', 'testing'))
    ->streets();
```

### price method

Retrieves price based on service, package, distance and other params.

**The `price()` method will return a double (standard service) or a json string (export service).**

```php
use Infifni\FanCourierApiClient\Client;
use Infifni\FanCourierApiClient\Request\Price;

// standard service
(new Client('7032158', 'clienttest', 'testing'))
    ->price([
        'serviciu' => Price::SERVICE_ALLOWED_VALUES['standard'],
        'localitate_dest' => 'Targu Mures',
        'judet_dest' => 'Mures',
        'plicuri' => 1,
        'colete' => 2,
        'greutate' => 5,
        'lungime' => 10,
        'latime' => 10,
        'inaltime' => 10,
        'val_decl' => 600,
        'plata_ramburs' => Price::SENDER_ALLOWED_VALUE,
        'plata_la' => Price::RECIPIENT_ALLOWED_VALUE
    ]);

// export service
(new Client('7032158', 'clienttest', 'testing'))
    ->price([
        'serviciu' => Price::SERVICE_ALLOWED_VALUES['export'],
        'modtrim' => Price::SEND_MODE_BY_AIR_ALLOWED_VALUE,
        'greutate' => 10.22,
        'pliccolet' => 3,
        's_inaltime' => 50,
        's_latime' => 67,
        's_lungime' => 48,
        'volum' => 400,
        'dest_tara' => 'Bulgaria',
        'tipcontinut' => 1,
        'km_ext' => 400,
        'plata_la' => Price::RECIPIENT_ALLOWED_VALUE
    ]);
```

### trackAwbMethod

Track expedition using AWB code. 

**The `trackAwb()` method will return a plain text.**

```php
use Infifni\FanCourierApiClient\Client;
use Infifni\FanCourierApiClient\Request\TrackAwb;

(new Client('7032158', 'clienttest', 'testing'))
    ->trackAwb([
        'AWB' => '2337600120003', // required
        'display_mode' => TrackAwb::DISPLAY_MODE_ALL_ALLOWED_VALUE, // required
        'language' => TrackAwb::LANGUAGE_RO_ALLOWED_VALUE // optional
    ]);
```


### generateAwb method

Send orders to generate AWB

**The `generateAwb()` method will return an array of objects with: line, awb, send_params, error_message.**

```php
use Infifni\FanCourierApiClient\Client;
use Infifni\FanCourierApiClient\Request\GenerateAwb;

(new Client('7032158', 'clienttest', 'testing'))
    ->generateAwb([
        'fisier' => [
            [
                'tip_serviciu' => GenerateAwb::SERVICE_ALLOWED_VALUES['standard'], 
                'banca' => '',
                'iban' =>  '',
                'nr_plicuri' => 1,
                'nr_colete' => 0,
                'greutate' => 1,
                'plata_expeditie' => 'ramburs',
                'ramburs_bani' => 100,
                'plata_ramburs_la' => GenerateAwb::RECIPIENT_ALLOWED_VALUE,
                'valoare_declarata' => 400,
                'persoana_contact_expeditor' => 'Test User',
                'observatii' => 'Lorem ipsum',
                'continut' => '',
                'nume_destinar' => 'Test',
                'persoana_contact' => 'Test',
                'telefon' => '123456789',
                'fax' => '123456789',
                'email' => 'example@example.com',
                'judet' => 'Galati',
                'localitate' => 'Tecuci',
                'strada' => 'Lorem',
                'nr' => '2',
                'cod_postal' => '123456',
                'bl' => '',
                'scara' => '',
                'etaj'  => '',
                'apartament' => '',
                'inaltime_pachet' => '',
                'lungime_pachet' => '',
                'restituire' => '',
                'centru_cost' => '',
                'optiuni' => '',
                'packing' => '',
                'date_personale' => ''
            ],
            [
                'tip_serviciu' => GenerateAwb::SERVICE_ALLOWED_VALUES['collector_account'],
                'banca' => 'Test',
                'iban' =>  'XXXXXX',
                'nr_plicuri' => 0,
                'nr_colete' => 1,
                'greutate' => 1,
                'plata_expeditie' => 'ramburs',
                'ramburs_bani' => 400,
                'plata_ramburs_la' => GenerateAwb::RECIPIENT_ALLOWED_VALUE,
                'valoare_declarata' => 400,
                'persoana_contact_expeditor' => 'Test User',
                'observatii' => 'Lorem ipsum',
                'continut' => 'Fragil',
                'nume_destinar' => 'Test',
                'persoana_contact' => 'Test',
                'telefon' => '123456789',
                'fax' => '123456789',
                'email' => 'example@example.com',
                'judet' => 'Galati',
                'localitate' => 'Tecuci',
                'strada' => 'Lorem',
                'nr' => '2',
                'cod_postal' => '123456',
                'bl' => '',
                'scara' => '',
                'etaj'  => '',
                'apartament' => '',
                'inaltime_pachet' => '',
                'lungime_pachet' => '',
                'restituire' => '',
                'centru_cost' => '',
                'optiuni' => '',
                'packing' => '',
                'date_personale' => ''
            ]
        ]]);
```

### order method

Place a order to a FanCourier Agent. The agent will come a pick-up the package at the requested hour, same day.

**The `order()` method will return a plain message if the request is being done successfully**

```php
use Infifni\FanCourierApiClient\Client;

(new Client('7032158', 'clienttest', 'testing'))
    ->order([
        'nr_colete' => 1,
        'pers_contact' => 'Test',
        'tel' => 123456789,
        'email' => 'example@example.com',
        'greutate' => 1,
        'inaltime' => 10,
        'lungime' => 10,
        'latime' => 10,
        'ora_ridicare' => '18:00',
        'observatii' => '',
        'client_exp' => 'Test',
        'strada' => 'Test',
        'nr' => 1,
        'bloc' => 2,
        'scara' => 3,
        'etaj' => 7,
        'ap' => 78,
        'localitate' => 'Constanta',
        'judet' => 'Constanta',
    ]);
```

### exportAwbErrors method

All FanCourier AWB with errors.

**The `exportAwbErrors()` method will return an empty array  or with objects containing: nume, judet, localitate, telefon, plicuri, colete, greutate, descriere.**

```php
use Infifni\FanCourierApiClient\Client;

(new Client('7032158', 'clienttest', 'testing'))
    ->exportAwbErrors();
```

### deleteAwb method

Deletes AWB only if the shipping process has not finished. 

**The `deleteAwb()` method will return an int (the deleted AWB number) if the request was successful or the error message.**

```php
use Infifni\FanCourierApiClient\Client;

(new Client('7032158', 'clienttest', 'testing'))
    ->deleteAwb([
        'AWB' => '2337600120003'
    ]);
```

### getAwb method

Return documents containing shipping details. 

**The `getAwb()` method will return a html page containing documents that can be printed if the request was successful or the error message.**

```php
use Infifni\FanCourierApiClient\Client;
use Infifni\FanCourierApiClient\Request\GetAwb;

(new Client('7032158', 'clienttest', 'testing'))
    ->getAwb([
        'nr' => '2337600120003',
        'page' => GetAwb::PAGE_A4_ALLOWED_VALUE,
        'ln' => GetAwb::LANGUAGE_RO_ALLOWED_VALUE
    ]);
```


### downloadAwb method

Returns AWB document in jpg format.

**The `downloadAwb()` method will return a jpg if the request is made successfully or the error message.**

```php
use Infifni\FanCourierApiClient\Client;
use Infifni\FanCourierApiClient\Request\DownloadAwb;

(new Client('7032158', 'clienttest', 'testing'))
    ->downloadAwb([
        'AWB'=>'2337600120003',
        'language' => DownloadAwb::LANGUAGE_RO_ALLOWED_VALUE
    ]);
```


### exportOrders method

All orders made within selected date through order method.

**The `exportOrders()` method will return an empty array  or with objects containing: nr._crt., data_ridicare_comanda, ora_de_la, ora_pana_la, persoana_contact, telefon, email, colete, numar_comanda, status.**

```php
use Infifni\FanCourierApiClient\Client;
use Infifni\FanCourierApiClient\Request\ExportOrders;

(new Client('7032158', 'clienttest', 'testing'))
    ->exportOrders([
        'data' => '22.05.2020',
        'language' => ExportOrders::LANGUAGE_RO_ALLOWED_VALUE
    ]);
```

### exportBordereau method

All orders made within selected date through generateAwb method.

**The `exportBordereau()` method will return an empty array  or with objects containing: nr._crt., awb, ridicat, status, data_confirmarii, restituire, tip_serviciu, continut...**

```php
use Infifni\FanCourierApiClient\Client;
use Infifni\FanCourierApiClient\Request\ExportBordereau;

(new Client('7032158', 'clienttest', 'testing'))
    ->exportBordereau([
        'data' => '22.05.2020',
        'language' => ExportBordereau::LANGUAGE_RO_ALLOWED_VALUE,
        'mode' => ExportBordereau::MODE_ALL_ALLOWED_VALUE
    ]);
```


### exportReports method

Returns all expeditions that have placed the total amount in the deposit account within selected date for the bank transfer.

**The `exportReports()` method will return an empty array  or with objects containing: oras_destinatar, dat_awb, suma_incasata, numar_awb, numar_awb, expeditor, destinatar, continut, persoanaD, data_virament, persoanaE, ramburs_la_awb, awb_retur**

```php
use Infifni\FanCourierApiClient\Client;
use Infifni\FanCourierApiClient\Request\ExportReports;

(new Client('7032158', 'clienttest', 'testing'))
    ->exportReports([
        'data' => '22.05.2020',
        'language' => ExportReports::LANGUAGE_RO_ALLOWED_VALUE
    ]);
```


### exportObservations method

Returns all observations that can be set when an expedition is being requested. 

**The `exportObservations()` method will return an empty array  or with objects containing: observatii_fan_courier**

```php
use Infifni\FanCourierApiClient\Client;

(new Client('7032158', 'clienttest', 'testing'))
    ->exportObservations();
```

### endBordereau method

Will close all orders made for the current date.
 
**The `endBordereau()` method will return a html.**

```php
use Infifni\FanCourierApiClient\Client;

(new Client('7032158', 'clienttest', 'testing'))
    ->endBordereau();
```



## Exceptions

The FanCourier package will throw exceptions if something goes wrong. This way it's easier to debug your code using the 
FanCourier package or to handle the error based on the type of exceptions.
The FanCourier packages can throw the following exceptions:

| Exception                             | 
| --------------------------------------|
| *FanCourierInstanceException*         | 
| *FanCourierInvalidParamException*     |                  
| *FanCourierUnknownRequestException*   |  
| *CsvWrongReadException*               |  