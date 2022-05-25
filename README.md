# API Template PHP

PHP + Lumen 建構API Service用的Template

## use

### Config

新增config設定

step1. Create config php file 

```shell
vi ./config/test.php
```

step2. Write config code to config file

```php
return [
    "Rapidash" => [
        "host" => "http://127.0.0.1/rapidash",
    ],
];

```

step3. load config

add config setting on bootstrap/app
```
<?php
    require_once __DIR__.'/../vendor/autoload.php';
    .
    .
    .
    $app->configure('test'); // 設置要載入config設定檔案
    .
    .
    .
    return $app;
```

### Exception 

新增例外處理

step1. Create Exception class. The class need to extends `ErrorHttpException` class and implement `ErrorHttpExceptionInterface` interface

```php
class ExampleErrorException extends ErrorHttpException implements ErrorHttpExceptionInterface
{
    public function __construct(
        int $code = 0,
        string $message = null,
        int $statusCode = 499,
        Exception $previous = null,
        array $headers = [],
        array $errors = null
    ) {
        parent::__construct($code, $message, $statusCode, $previous, $headers, $errors);
    }
}
```

step2. Load Exception Class

```php
class Handler extends ExceptionHandler
{
    ...
    
    protected $dontReport = [
        ...
        ExampleErrorException::class, // 要載入例外處理Class
    ]
  
    ...
}

```
