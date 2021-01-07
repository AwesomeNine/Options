# Options

[![Awesome9](https://img.shields.io/badge/Awesome-9-brightgreen)](https://awesome9.co)
[![Latest Stable Version](https://poser.pugx.org/awesome9/options/v/stable)](https://packagist.org/packages/awesome9/options)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/awesome9/options.svg)](https://packagist.org/packages/awesome9/options)
[![Total Downloads](https://poser.pugx.org/awesome9/options/downloads)](https://packagist.org/packages/awesome9/options)
[![License](https://poser.pugx.org/awesome9/options/license)](https://packagist.org/packages/awesome9/options)

<p align="center">
	<img src="https://img.icons8.com/nolan/256/options.png"/>
</p>

## 📃 About Options

This package provides ease of managing options within WordPress.

## 💾 Installation

``` bash
composer require awesome9/options
```

## 🕹 Usage

First, you need to register options for your theme/plugin.

```php
Awesome9\Options\Options::get()
	->register(
		'awesome9',                 // Unique name which also can be used as function
		'_awesome9_plugin_options', // Option key for database
		// Defaults values.
		[
			'name' => 'Awesome9',
			'social' => [
				'facebook' => 'https://facebook.com',
				'twitter'  => 'https://twitter.com',
			]
		]
	);
```

On plugin activation hook you can use `install` method to add default option into system.

```php
Awesome9\Options\Options::get()->install();
```
You can get value for an option you can use the option name to get value from it. You can also use id as path to get value.

```php
Awesome9\Options\Options::get()->awesome9( 'name' ); // Awesome9
Awesome9\Options\Options::get()->awesome9( 'social.facebook' ); // https://facebook.com
```

You can update values from option temporary for session or permenantly.

```php
// Temporary update for session.
Awesome9\Options\Options::get()->awesome9()->update( 'name', 'Awesome Nine' );

// Permenant update
Awesome9\Options\Options::get()->awesome9()->update( 'name', 'Awesome Nine', true );
```

You can remove values from option temporary for session or permenantly.

```php
// Temporary remove for session.
Awesome9\Options\Options::get()->awesome9()->remove( 'name' );

// Permenant remove
Awesome9\Options\Options::get()->awesome9()->remove( 'name', true );
```

All the parameters remains the same as for the `JSON` class.

## 📖 Changelog

[See the changelog file](./CHANGELOG.md)
