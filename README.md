[![Build Status](https://travis-ci.org/hollodotme/state-machine-generator.svg?branch=master)](https://travis-ci.org/hollodotme/state-machine-generator)
[![Coverage Status](https://coveralls.io/repos/github/hollodotme/state-machine-generator/badge.svg?branch=master)](https://coveralls.io/github/hollodotme/state-machine-generator?branch=master)

# State machine generator

PHP 7.1 code generator for OOP state machines

This package is based on [Sebastian Bergmann's state repository](https://github.com/sebastianbergmann/state), with the following changes:

* Code was updated for compatibility with php >= 7.1
* Command based CLI api
* Only the code generation part was extracted
* Namespaces were added
* States can be represented as and reconstituted from strings
* Output paths for main class, state classes and interface, test can be configured in the specification
* Command for creating a template specification file
* Test class update for PHPUnit 6
* Generator is distributed as a PHAR file

## Usage

### Create a sample sepcification file

```bash
$ php state-machine-generator.phar create:specfile /path/to/specification-file.xml
```

### Generate state machine classes incl. tests

```bash
$ php state-machine-generator.phar generate:machine [options] /path/to/specification-file.xml
```

