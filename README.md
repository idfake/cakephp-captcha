# Captcha Plugin for CakePHP
[![Build Status](https://api.travis-ci.org/dereuromark/cakephp-captcha.svg)](https://travis-ci.org/dereuromark/cakephp-captcha)
[![Coverage Status](https://coveralls.io/repos/dereuromark/cakephp-captcha/badge.svg)](https://coveralls.io/r/dereuromark/cakephp-captcha)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/cakephp-captcha/license)](https://packagist.org/packages/dereuromark/cakephp-captcha)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-captcha/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-captcha)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

Allows any type of image-based captchas for your forms.

## A CakePHP 3.x plugin to
- Easily integrate captchas in your CakePHP application.

### Whats in this plugin
- Simple match captchas that will already do the trick without annoying "I can't read those letters".
- Extendable interface approach to easily hook in your own engine.

### Whats the gotchas
- Dead simple, no fancy JS or alike.
- Cross-tab safe (not session based as in overwriting each other per request).
- Completely stand-alone plugin, no third party stuff involved/needed.

## Demo
See http://sandbox3.dereuromark.de/sandbox/captchas

## Setup
```
composer require dereuromark/cakephp-captcha
```
and
```
bin/cake plugin load Captcha
```

You also need to import the SQL schema.
The quickest way is using Migrations plugin:
```
bin/cake Migrations migrate -p Captcha
```

## Basic Usage
Using the default MathEngine we can simply attach the behavior to the Table class.

```php
// Adding the behavior in your Table initialize()
$this->addBehavior('Captcha.Captcha', ['complexity' => 2, ...]);

// Saving a new ad would now require a valid captcha solution
$dd = [
	'title' => 'Looking for a friend'
];
$ad = $this->Ads->newEntity($postData);
$this->Users->save($user);
```