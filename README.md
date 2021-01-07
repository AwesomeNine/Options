# JSON

[![Awesome9](https://img.shields.io/badge/Awesome-9-brightgreen)](https://awesome9.co)
[![Latest Stable Version](https://poser.pugx.org/awesome9/json/v/stable)](https://packagist.org/packages/awesome9/json)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/awesome9/json.svg)](https://packagist.org/packages/awesome9/json)
[![Total Downloads](https://poser.pugx.org/awesome9/json/downloads)](https://packagist.org/packages/awesome9/json)
[![License](https://poser.pugx.org/awesome9/json/license)](https://packagist.org/packages/awesome9/json)

<p align="center">
	<img src="https://img.icons8.com/nolan/256/json.png"/>
</p>

## 📃 About JSON

This package provides ease of managing data localization within WordPress.

## 💾 Installation

``` bash
composer require awesome9/json
```

## 🕹 Usage

First, you need to spin out configuration for your json.

```php
Awesome9\JSON\JSON::get()
	->set_object_name( 'awesome9' );  // Default object name to be output.
```

Now, let's add and remove some data to be output in admin.

```php
Awesome9\JSON\JSON::get()
	->add( 'company', 'great' )
	->add( 'remove', 'me' )
	->add( 'array', array(
		'a' => 'test',
		'b' => 'test',
	) );

Awesome9\JSON\JSON::get()
	->remove( 'remove' )
```

And you can use it in your JavaScript files as
```js
console.log( awesome9.company );
console.log( awesome9.array.a );
```

### Available JSON methods

JSON class methods.

| Method                                                                         | Description              | Returns                                                      |
| ------------------------------------------------------------------------------ | ------------------------ | ------------------------------------------------------------ |
| ```add( (string) $key, (mixed) $value, (string) $object_name )```              | Add the variable         | `$this`                                                      |
| ```remove( (string) $var_name, (string) $object_name ) )```                    | Removes the variable     | `$this`                                                      |
| ```clear_all()```                                                              | Clears all data          | `$this`                                                      |
| ```output()```                                                                 | Outputs the JSON data    |                                                              |

### Helper functions

You can use the procedural approach as well:

```php
Awesome9\JSON\add( $key, $value, $object_name = false );

Awesome9\JSON\remove( $key, $object_name = false );
```

All the parameters remains the same as for the `JSON` class.

## 📖 Changelog

[See the changelog file](./CHANGELOG.md)
