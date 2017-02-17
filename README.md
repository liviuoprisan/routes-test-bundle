Oldev Routes Test Testing Bundle
===================
This bundle comes with a test that will automaticaly check all get routes and test if the response code is 200.
It will try to use default parameters.
There is a way to easy exclude routes.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require <package-name> "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

How to use it
-------------
Extend the AbstractRoutesTest in one of your tests. Add any routes that can't work with defaults as exceptions like in AbstractRoutesTest.
Run the test.


	
Bundle Authors
--------------

	- Liviu Oprisan <liviu.oprisan@orange.com>
	- Samuel Chiriluta <samuel.chiriluta@orange.com>
