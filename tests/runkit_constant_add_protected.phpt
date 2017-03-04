--TEST--
runkit_constant_redefine() function redefines protected class constants (when accessing other files, not working for same file)
--SKIPIF--
<?php
if(!extension_loaded("runkit") || !RUNKIT_FEATURE_MANIPULATION) print "skip";
if (PHP_MAJOR_VERSION == 7 && PHP_MINOR_VERSION == 0) { print "skip no const visibility in 7.0"; }
?>
--FILE--
<?php
// For this test case to actually work (The php compiler, not opcache, does this optimization), the code would need to be compiled with ZEND_COMPILE_NO_CONSTANT_SUBSTITUTION | ZEND_COMPILE_NO_PERSISTENT_CONSTANT_SUBSTITUTION
// That requires either patching php-src or triggering recompilation of code in affected class and in subclasses.
class TestClass {
	public static function get_foo() {
		// missing unless added by runkit
		return self::_FOO;
	}
}

class TestSubclass extends TestClass {
	public static function get_parent_foo() {
		// missing unless added by runkit
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

error_reporting(E_ALL);

access_protected_constant();
$const = 'TestClass::_FOO';
try {
	$x = TestClass::get_foo();
} catch (Error $e) {
	printf("Caught %s from get_foo: %s\n", get_class($e), $e->getMessage());
}
runkit_constant_add($const, 'roh', RUNKIT_ACC_PROTECTED);
var_dump($const);
var_dump(TestClass::get_foo());
var_dump(TestSubclass::get_foo());
access_protected_constant();
// TODO: Remove public/protected constants from subclasses automatically, if they're the same value and visibility?
// I forget if upstream does this.
runkit_constant_remove('TestSubclass::_FOO');
runkit_constant_remove($const);
try {
	var_dump(TestClass::get_foo());
	echo "Unexpectedly able to fetch removed constant from TestClass\n";
} catch (Error $e) {
	printf("Caught %s from get_foo: %s\n", get_class($e), $e->getMessage());
}
try {
	var_dump(TestSubclass::get_parent_foo());
	echo "Unexpectedly able to fetch removed constant from TestSubclass::get_parent_foo()\n";
} catch (Error $e) {
	printf("Caught %s from TestSubclass::get_parent_foo: %s\n", get_class($e), $e->getMessage());
}
runkit_constant_add($const, 'dah', RUNKIT_ACC_PRIVATE);
var_dump($const);
var_dump(TestClass::get_foo());
var_dump(TestSubclass::get_foo());
try {
	var_dump(TestSubclass::get_parent_foo());
	echo "Unexpectedly able to fetch private constant of TestClass from a method declared in TestSubclass\n";
} catch (Error $e) {
	printf("Caught %s from TestSubclass::get_parent_foo: %s\n", get_class($e), $e->getMessage());
}

?>
--EXPECT--
Caught Error: Undefined class constant '_FOO'
Caught Error from get_foo: Undefined class constant '_FOO'
string(15) "TestClass::_FOO"
string(3) "roh"
string(3) "roh"
Caught Error: Cannot access protected const TestClass::_FOO
Caught Error from get_foo: Undefined class constant '_FOO'
Caught Error from TestSubclass::get_parent_foo: Undefined class constant '_FOO'
string(15) "TestClass::_FOO"
string(3) "dah"
string(3) "dah"
Caught Error from TestSubclass::get_parent_foo: Undefined class constant '_FOO'
