--TEST--
runkit_object_id should fetch the object handle.
--SKIPIF--
<?php
if(!extension_loaded("runkit")) print "skip";
?>
--INI--
error_reporting=E_ALL
--FILE--
<?php
var_dump(runkit_object_id([]));
$x = new stdClass();
$y = new stdClass();
var_dump(runkit_object_id($x));
var_dump(runkit_object_id($y));
var_dump(runkit_object_id($x));

?>
--EXPECTF--
Warning: runkit_object_id() expects parameter 1 to be object, array given in %s on line %d
bool(false)
int(1)
int(2)
int(1)
