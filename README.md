Carnival
========

[![Build Status](https://travis-ci.org/aztech-digital/carnival.svg)](https://travis-ci.org/aztech-digital/carnival)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/coverage/g/aztech-digital/carnival.svg?style=flat)](https://scrutinizer-ci.com/g/aztech-digital/carnival/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/g/aztech-digital/carnival.svg?style=flat)](https://scrutinizer-ci.com/g/aztech-digital/carnival/?branch=master)

Carnival is a simple library to generate static facades from instance objects. In other words, it takes an object, hides it behind a generated static class, effectively transforming your object into a singleton as long as you access it from the static facade.

## Installation

[Composer](https://getcomposer.org) is the only supported way of installing and using Carnival. From your project's root directory, run:

```
composer require aztech/carnival
```

You must then include the Composer autoloader in the entry file of your application:

```
<?php

require_once __DIR__ . 'vendor/autoload.php';
```

## Dafuq is wrong with you ?

Probably a lot of things. But that fails to answer the question, why this library. Because ! 

Ok, originally, I wrote it as a troll against Laravel's facades. But if anyone finds it useful, go ahead, have fun !

## And you're using `eval` ?

Isn't that the root of all evil ? Probably, but it's used in a constrained way, and honestly, if you plan on passing tainted data to Masquerade, allow me to ask **dafuq is wrong with you** ?

## So now that its uselessness is settled, how do I use it anyways ?

Simple, bind whatever object you want to a new, non-existing class name (you can even namespace your facade !) like that:

```php

// This call is only required once, you can place it in your application bootstrap or wherever.
Aztech\Carnival\Masquerade::register(); 

// Bind an object to a class name
Aztech\Carnival\Masquerade::bind('\Facades\SomeArray', new \SplObjectStorage());

// Use your facade
Facades\SomeArray::attach(new \stdClass());
```

Yup, simple as that.
