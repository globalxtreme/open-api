GlobalXtreme Open API
======

[![Version](http://poser.pugx.org/globalxtreme/open-api/version)](https://packagist.org/packages/globalxtreme/open-api)
[![Total Downloads](http://poser.pugx.org/globalxtreme/open-api/downloads)](https://packagist.org/packages/globalxtreme/open-api)
[![License](http://poser.pugx.org/globalxtreme/open-api/license)](https://packagist.org/packages/globalxtreme/open-api)

### Install with composer

To install with [Composer](https://getcomposer.org/), simply require the
latest version of this package.

```bash
composer require globalxtreme/open-api
```

## Using
- For example, we'll generate new credential for Customer Support to CRM
- **[CRM & Customer Support]** Publish the configuration to **config/open-api.php** file.
    ```bash
    php artisan vendor:publish --provider="GlobalXtreme\OpenAPI\OpenAPIServiceProvider" 
    ```
- **[CRM]** Generate new credential **"php artisan open-api-credential name?"**
    ```bash
    php artisan open-api-credential customer-support 
    ```
- **[CRM]** Copy credential to **"config/open-api.php"**
    ```php
    'credentials' => [

        // Copy with the same name? "customer-support"
        'customer-support' => [
            'id' => env('OPEN_API_CUSTOMER_SUPPORT_ID', ''),
            'key' => env('OPEN_API_CUSTOMER_SUPPORT_KEY', '')
        ],

    ]
    ```
- **[CRM]** Add credential to **".env"**
    ```php
    OPEN_API_CUSTOMER_SUPPORT_ID=<client-id>
    OPEN_API_CUSTOMER_SUPPORT_KEY=<client-key>
    ```
- **[Customer Support]** Copy credential to service project / client configuration **"config/open-api.php"**
    ```php
    'clients' => [

        'CRM' => [
            'host' => env('CRM_HOST', ''), // Server host
            'client-id' => env('CRM_CLIENT_ID', ''),
            'client-name' => env('CRM_CLIENT_NAME', ''),
            'client-secret' => env('CRM_CLIENT_SECRET', ''),
        ],

    ]
    ```
- **[Customer Support]** Add credential to **".env"** server project / client
    ```php
    CRM_HOST=http://127.0.0.1/
    CRM_CLIENT_ID=<client-id>
    CRM_CLIENT_NAME=<client-name>
    CRM_CLIENT_SECRET=<client-public-key>
    ```