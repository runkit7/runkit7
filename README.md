Runkit7: Unofficial runkit extension fork for PHP 7.0+
======================================================

For all those things you.... probably shouldn't have been doing anyway.... but surely do!
__Now with partial support for PHP7.0 and PHP7.1!__ (function/method manipulation is recommended only for unit testing).

[![Build Status](https://secure.travis-ci.org/runkit7/runkit7.png?branch=master)](http://travis-ci.org/runkit7/runkit7)
[![Build Status (Windows)](https://ci.appveyor.com/api/projects/status/3jwsf76ge0yo8v74?svg=true)](https://ci.appveyor.com/project/TysonAndre/runkit7)

[Building and installing runkit in unix](#building-and-installing-runkit7-in-unix)

[Building and installing runkit in Windows](#building-and-installing-runkit7-in-windows)

Current Build Status
--------------------

In 7.0.x and 7.1.x: 0 failing tests, 4 expected failures (constant manipulation in same file), 61 skipped tests(for disabled property and import), and 95 passing tests.

Compatability: PHP7.0 and PHP7.1(Partial)
-----------------------------------------

See [runkit-api.php](./runkit-api.php) for the implemented functionality and method signatures.

Superglobals work reliably when tested on web servers and tests.
Class and function manipulation is recommended only for unit tests.

- `runkit-superglobal` works reliably in 7.0.x and 7.1.x. Superglobals will be unavailable during request shutdown, e.g. when the session is being saved, when other extensions are shutting down.
- Manipulating user-defined (i.e. not builtin or part of extensions) functions and methods via `runkit_method_*` and `runkit_function_*` generally works, **but is recommended only in unit tests** (unlikely to crash, but will cause memory leaks)
- Manipulating built in functions may cause segmentation faults in rare cases.
  File a bug report if you see this.
  **this is recommended only in unit tests, because of the possibility of crashes**.
  (Manipulating built in class methods is impossible/not supported)
- Adding default properties to classes doesn't work in php7, because of a change
  in the way PHP stores objects.
  Eventually, I plan to add `runkit_default_property_modify`, which will replace one default value with a different default property, keeping the number of properties the same.
  See the reasons for disabling property manipulation at [PROPERTY\_MANIPULATION.md](./PROPERTY_MANIPULATION.md)
  As a substitute, user code can do the following things:

  - rename (or add) `__construct` with `runkit_method_rename`/`runkit_method_add`,
    and create a new version of `__construct` that sets the properties, then calls the original constructor.
  - For getting/setting properties of **individual objects**, see [ReflectionProperty](https://secure.php.net/manual/en/class.reflectionproperty.php)
    `ReflectionProperty->setAccessible(true)` and `ReflectionProperty->setValue()`, etc.
- Modifying constants works for constants declared in different files, but does not work for constants within the same file.
  PHP7.0+ inlines constants within the same file if they are guaranteed to have only one definition.
  Patching php-src and/or opcache to not inline constants (e.g. based on a php.ini setting) is possible, but hasn't been tried yet.
- Sandboxing (and `runkit_lint`) were removed.
- `runkit_object_id` works.

The following contributions are welcome:

-	Pull requests with  PHP5 -> PHP7 code migration of functions
-	New test cases for features that no longer work in PHP7, or code crashing runkit7.
-	Issues for PHP language features that worked in PHP5, but no longer work in PHP7,
	for the implemented methods (`runkit_function_*` and `runkit_method_*`)
-   Fixes and documentation.

Most of the runkit tests for method manipulation and function manipulation are passing.
Other methods and corresponding tests are disabled/skipped because changes to php internals in php7 made them impractical.

Examples
--------

The following mocking libraries work with the runkit7 fork

- [timecop-PHP (Fork)](https://github.com/runkit7/Timecop-PHP) (requires `runkit.internal_override=1`, suggested only for unit tests)
- [staticmock (Fork)](https://github.com/runkit7/staticmock)

## PHP7 SPECIFIC DETAILS

### Bugs in PHP7 runkit

-	There are segmentation faults when manipulating internal functions
	(a.k.a. "runkit.internal_override=1")
	(when you rename/redefine/(copy?) internal functions, and call internal functions with user functions' implementation, or vice versa)
	(and when functions redefinitions aren't cleaned up)
	Many of these have been fixed.
-	There are reference counting bugs causing memory leaks.
	2 calls to `emalloc` have been temporarily replaced with calls to `pemalloc`
	so that I could execute tests.
-	There may be a few remaining logic errors after migrating the code to PHP7.
-	The zend VM bytecode may change in 7.2, so some opcodes may not work with each new minor php version release.

### APIs for PHP7
#### Implemented APIs for PHP7 (buggy internal function manipulation):

-	`runkit_function_*`: Most tests are passing. There are some bugs related to renaming internal functions.
-	`runkit_method_*`: Most tests are passing. Same comment as `runkit_function_*`
-	`runkit_zval_inspect`: Partly passing, and needs to be rewritten because of PHP7's zval changes.
-	`runkit_constant_add` works. Other constant manipulation functions don't work for constants within the same file due to the interpreter inlining them.
-	Runkit superglobals.

#### Unsupported APIs for PHP7:
(These functions will be missing)

-	`runkit_import`
	Disabled because of bugs related to properties
	See https://github.com/runkit7/runkit7/issues/73
-	`runkit_class_adopt` and `runkit_class_emancipate`
	Disabled because of [bugs related to properties](./PROPERTY_MANIPULATION.md).
-	`runkit_lint*`
-	`runkit_constant_*` : `runkit_constant_add` works reliably, other methods don't.
	This works better when the constants are declared in a different file.
-	`runkit_default_property_*`
	Disabled because of [bugs related to properties](./PROPERTY_MANIPULATION.md)
	See https://github.com/runkit7/runkit7/issues/30

	`runkit_default_property_add` has been removed in php7 - it requires `realloc`ing a different zval to add a property to the property table
	That would break a lot of things.
-	`runkit_return_value_used`: Removed, was not working and unrelated to other features.
	`vld` seems to have a working implementation in the opcode analyzer, not familiar with how it works.

#### Reasons for disabling property manipulation

See [PROPERTY\_MANIPULATION.md](./PROPERTY_MANIPULATION.md)

### FURTHER WORK

See https://github.com/runkit7/runkit7/issues

Things to do in the near future:

-   Fix bugs related to edge cases of function and method manipulation
-   See if constant manipulation in the same file can be fixed, e.g. by recompiling functions using those constants, or by patching php-src.
    It was broken because the php7 compiler inlines the constants automatically in the generated opcodes.

Things to do after that:

-   Replace property manipulation with `runkit_default_property_modify` (https://github.com/runkit7/runkit7/issues/30)
-	Fix FPM

UPSTREAM DOCUMENTATION
======================

**(runkit7 is an unofficial fork of https://github.com/zenovich/runkit, adding php7 support)**

Features
========

Runkit has two groups of features outlined below (Sandboxing was removed in runkit7):

### CUSTOM SUPERGLOBALS
A new .ini entry `runkit.superglobal` is defined which may be specified as a simple variable, or list of simple variables to be registered as
superglobals.  runkit.superglobal is defined as PHP_INI_SYSTEM and must be set in the system-wide php.ini.

Example:

php.ini:
```ini
runkit.superglobal=foo,bar
```

test.php:
```php
function testme() {
  echo "Foo is $foo\n";
  echo "Bar is $bar\n";
  echo "Baz is $baz\n";
}
$foo = 1;
$bar = 2;
$baz = 3;

testme();
```

Outputs:
```
Foo is 1
Bar is 2
Baz is
```


### USER DEFINED FUNCTION AND CLASS MANIPULATION
__NOTE: Only a subset of the APIs have been ported to PHP7. Some of these APIs have segmentation faults in corner cases __ (when `runkit.internal_override=On`)

User defined functions and user defined methods may now be renamed, delete, and redefined using the API described at http://www.php.net/runkit

Examples for these functions may also be found in the tests folder.

As a replacement for `runkit_lint`/`runkit_lint_file` try any of the following:

- `php -l --no-php-ini $filename` will quickly check if a file is syntactically valid, but will not show you any php notices about deprecated code, etc.
- [`opcache_compile_file`](https://secure.php.net/manual/en/function.opcache-compile-file.php) may help, but will not show you any notices.
- Projects such as [PHP-Parser (Pure PHP)](https://github.com/nikic/PHP-Parser) and [php-ast (C module)](https://github.com/nikic/php-ast, which produce an Abstract Syntax Tree from php code.
  php-ast (PHP module) has a function is much faster and more accurate.
  (Unfortunately, it parses but does not detect erroneous code, e.g. duplicate classes/methods in the same file).

  ```php
  // Example replacement for runkit_lint.
  try {
      $ast = ast\parse_code('<?php function foo(){}', $version = 35)
	  return true;
  }catch (ParseError $e) {
	  return false;
  }
  ```

Installation
============


### BUILDING AND INSTALLING RUNKIT(7) IN UNIX

```bash
git clone https://github.com/runkit7/runkit7.git
cd runkit7
phpize
# The sandbox related code and flags have been removed, no need to disable them.
# (--enable-runkit-modify (on by default) controls function, method, class, manipulation, and will control property manipulation)
# (--enable-runkit-super (on by default) allows you to add custom superglobals)
./configure
make
make test
sudo make install
```

Pecl tars are also included with newer GitHub releases.

1. Go to https://github.com/runkit7/runkit7/releases
2. Download the tgz file from the link (e.g. runkit-1.0.5a2.tgz)
3. `pecl install ./runkit-1.0.5a2.tgz`

### BUILDING AND INSTALLING RUNKIT7 IN WINDOWS

#### Setting up php build environment

Read https://wiki.php.net/internals/windows/stepbystepbuild first. This is just a special case of these instructions.

For PHP7, you need to install "Visual Studio 2015 Community Edition" (or other 2015 edition).
Make sure that C++ is installed with Visual Studio.
The command prompt to use is "VS2015 x86 Native Tools Command Prompt" on 32-bit, "VS2015 x64 Native Tools Command Prompt" on 64-bit.

For 64-bit installations of php7, use "x64" instead of "x86" for the below commands/folders.

After completing setup steps mentioned, including for `C:\php-sdk\phpdev\vc14`

extract download of php-7.0.9-src (or any version of php 7) to C:\php-sdk\phpdev\vc14\x86\php-7.0.9-src

#### Installing runkit7 on windows

There are currently no sources providing DLLs of this fork. Runkit7 and other extensions used must be built from source.

Create subdirectory C:\php-sdk\phpdev\vc14\x86\pecl, adjacent to php source directory)

extract download of runkit7 to C:\php-sdk\phpdev\vc14\x86\pecl\runkit7 (all of the c files and h files should be within runkit7, pecl is

Then, execute the following (Add `--enable-runkit` to the configure flags you were already using)

```Batchfile
cd C:\php-sdk
C:\php-sdk\bin\phpsdk_setvars.bat
cd phpdev\vc14\x86\php-7.0.9\src
buildconf
configure --enable-runkit
nmake
```

Then, optionally test it (Most of the tests should pass, around 16 are still failing):

```Batchfile
nmake test TESTS="C:\php-sdk\vc14\x86\pecl\igbinary7\tests
```
