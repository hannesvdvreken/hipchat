# [Hipchat](https://hipchat.com) notifier
[![Build Status](https://travis-ci.org/hannesvdvreken/hipchat.png?branch=master)](https://travis-ci.org/hannesvdvreken/hipchat) [![Latest Stable Version](https://poser.pugx.org/hannesvdvreken/hipchat/v/stable.png)](https://packagist.org/packages/hannesvdvreken/hipchat) [![Total Downloads](https://poser.pugx.org/hannesvdvreken/hipchat/downloads.png)](https://packagist.org/packages/hannesvdvreken/hipchat)

## Usage

Getting started with a *Hello World* example.

```php
// Configuration.
$rooms = array(
	array(
		'room_id' => '1234',
		'auth_token' => '****',
	),
);

// Create the required Guzzle client.
$client = new \Guzzle\Http\Client;
$hipchat = new \Hipchat\Notifier($client, $rooms);

// Send the notification.
$hipchat->notify('Hello world!');
```

If you would like to send notification to different rooms, add some to the array.

```php
// Configuration.
$rooms = array(
	'frontenders' => array(
		'room_id' => '1234',
		'auth_token' => '****',
	),
	'backenders' => array(
		'room_id' => '5678',
		'auth_token' => '****',
	),
);

// Create the required Guzzle client.
$client = new \Guzzle\Http\Client;
$hipchat = new \Hipchat\Notifier($client, $rooms);

// Send the notification.
$hipchat->notifyIn('frontenders', 'Hello world!');
```

The default room in which the `notify` method posts to is the first from the array, or you can
specify which room to use as a default with a third constructor parameter:

### Extra configuration.
The *constructor* accepts a third parameter with extra options.

```php
$config = array(
	'default' => 'frontenders',
	'color' => 'gray',
	'pretend' => true,
	'notify' => true,
);
```

- `default`: The default room to send notifications in with `->notify()`.
- `color`: Choose from `yellow`, `red`, `green`, `purple`, `gray` or `random`.
- `pretend`: Don't actually send any messages.
- `notify`: Let hipchat make a sound.

### Color

Choose your color depending on the type of message with the second and third parameter of the 
`notify` and `notifyIn` method.

```php
// Example 1
$color = $error ? 'red' : 'green';
$hipchat->notify($message, $color);

// Example 2
$hipchat->notifyIn('frontenders', $message, 'purple');
```

## Laravel 4

This package comes with a Laravel 4 service provider. Add the following line to the
providers array in `app.php`.

```php
'Hipchat\Support\ServiceProvider',
```

It also registers an alias for the Facade class `Hipchat\Support\Facades\Hipchat` so you can just use
`Hipchat::notify($message)` and `Hipchat::notifyIn('frontenders', $message)`.

Publish the default configuration with the following command:

```bash
php artisan config:publish hannesvdvreken/hipchat
```

All configurable options can be found there.

## Contributing
Feel free to make a pull request. Please try to be as 
[PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) 
compliant as possible.

## License

MIT
