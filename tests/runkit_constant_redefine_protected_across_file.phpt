--TEST--
runkit_constant_redefine() function redefines protected class constants (when accessing other files, not working for same file)
--SKIPIF--
<?php
if(!extension_loaded("runkit") || !RUNKIT_FEATURE_MANIPULATION) print "skip";
if (PHP_MAJOR_VERSION == 7 && PHP_MINOR_VERSION == 0) { print "skip no const visibility in 7.0"; }
?>
--FILE--
<?php
require_once __DIR__ . '/runkit_constant_redefine_protected_across_file.inc';
// For this test case to actually work (The php compiler, not opcache, does this optimization), the code would need to be compiled with ZEND_COMPILE_NO_CONSTANT_SUBSTITUTION | ZEND_COMPILE_NO_PERSISTENT_CONSTANT_SUBSTITUTION
// That requires either patching php-src or triggering recompilation of code in affected class and in subclasses.
class TestClass extends TestBaseClass{
	public static function get_foo() {
		return self::_FOO;
	}
}


function access_protected_constant() {
	try {
		$x = TestClass::_FOO;
		printf("Fetched constant, value=%s\n", var_export($x, true));
	} catch (Error $e) {
		printf("Caught %s: %s\n", get_class($e), $e->getMessage());
	}
}
access_protected_constant();
$const = 'TestBaseClass::_FOO';
var_dump($const, TestClass::get_foo());
runkit_constant_redefine($const, 'roh');
var_dump($const, TestClass::get_foo());
$x = TestClass::get_foo();
runkit_constant_redefine($const, $x);
var_dump($const, TestClass::get_foo());
runkit_constant_redefine($const, ['dah']);
var_dump($const, TestClass::get_foo());
runkit_constant_redefine($const, 2);
var_dump($const, TestClass::get_foo());
access_protected_constant();
// Redefine it as public, and the access should then work.
runkit_constant_redefine($const, "bar", RUNKIT_ACC_PUBLIC);
var_dump($const, TestClass::get_foo());
access_protected_constant();
// TODO test subclass
?>
--EXPECT--
Caught Error: Cannot access protected const TestClass::_FOO
string(19) "TestBaseClass::_FOO"
string(3) "foo"
string(19) "TestBaseClass::_FOO"
string(3) "roh"
string(19) "TestBaseClass::_FOO"
string(3) "roh"
string(19) "TestBaseClass::_FOO"
array(1) {
  [0]=>
  string(3) "dah"
}
string(19) "TestBaseClass::_FOO"
int(2)
Caught Error: Cannot access protected const TestClass::_FOO
string(19) "TestBaseClass::_FOO"
string(3) "bar"
Fetched constant, value='bar'
