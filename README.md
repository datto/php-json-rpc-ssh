# JSON-RPC for PHP

## Features

* Fully compliant with the [JSON-RPC 2.0 specifications](http://www.jsonrpc.org/specification) (with 100% unit-test coverage)
* Versatile:
  * You can make JSON-RPC requests over HTTP or SSH, or locally through PHP
  * You can choose your own system for interpreting the JSON-RPC method strings
* Minimalistic and flexible. Works even when CURL is not installed.

## Requirements

* PHP >= 5.3

## License

This package is released under an open-source license: [LGPL-3.0](https://www.gnu.org/licenses/lgpl-3.0.html)

## Examples

### Client

```php
$client = new Client($destination, $command, $options);

$client->query(1, 'add', array(1, 2));

$reply = $client->send(); // array('jsonrpc' => '2.0', 'id' => 1, 'result' => 3)
```

### Server

```php
$translator = new MethodTranslator();

$server = new Server($translator);

$server->reply();
```

*See the "examples" folder for ready-to-use examples.*

## Installation

If you're using [Composer](https://getcomposer.org/), you can use this package
by inserting a line in the "require" section of your "composer.json" file:
```
        "datto/php-json-rpc": "3.0.*"
```


## Getting started

1. Try the examples! The "Php" example works right out of the box:
	```
	php examples/Php/client.php
	```
	For the other examples, you'll need to follow the README file to set up the
	necessary environment first.

2. Once your example is working, replace the method translator and the server-side
code from the "Example/Stateless" directory with your own code.

3. Write a clean wrapper around the JSON-RPC client class that will dovetail
nicely with your project.

## Unit tests

You can run the suite of unit tests from the project directory like this:
```
./vendor/bin/phpunit
```

## Author

[Spencer Mortensen](http://spencermortensen.com/contact/)
