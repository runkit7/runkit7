<?php
// The runkit7 fork has a slightly different API from what is documented at https://secure.php.net/runkit
//
// - Return types were added for php7.0
// - Some functions are unsupported

// These constants depend on the php version, and may change.
const RUNKIT_ACC_RETURN_REFERENCE = 0x4000000;
const RUNKIT_ACC_PUBLIC           = 0x100;
const RUNKIT_ACC_PROTECTED        = 0x200;
const RUNKIT_ACC_PRIVATE          = 0x400;
const RUNKIT_ACC_STATIC           = 0x1;

const RUNKIT_VERSION              = "1.0.5a2";


/**
 * Similar to define(), but allows defining in class definitions as well.
 *
 * NOTE: Constants and class constants within the same file may be inlined by the Zend VM optimizer,
 * and this may or may not have an effect if the constant already exists.
 *
 * @param string $constname Name of constant to declare. Either a string to indicate a global constant, or classname::constname to indicate a class constant.
 * @param mixed $value null, Bool, Long, Double, String, Resource, or Array value to store in the new constant.
 * @return bool - TRUE on success or FALSE on failure.
 */
function runkit_constant_add(string $constname, $value) : bool {
}

/**
 * Similar to define(), but allows defining in class definitions as well.
 *
 * NOTE: Constants and class constants within the same file may be inlined by the Zend VM optimizer,
 * and this may or may not have an effect if the constant already exists.
 *
 * @param string $constname Name of constant to declare. Either a string to indicate a global constant, or classname::constname to indicate a class constant.
 * @param mixed $value null, Bool, Long, Double, String, Resource, or Array value to store in the new constant.
 * @return bool - TRUE on success or FALSE on failure.
 */
function runkit_constant_redefine(string $constname, $value) : bool {
}

/**
 * Remove/Delete an already defined constant
 *
 * NOTE: Constants and class constants within the same file may be inlined by the Zend VM optimizer,
 * and this may or may not have an effect if the constant already exists.
 *
 * @param string $constname Name of constant to declare. Either a string to indicate a global constant, or classname::constname to indicate a class constant.
 * @param mixed $value null, Bool, Long, Double, String, or Resource value to store in the new constant.
 * @return bool - TRUE on success or FALSE on failure.
 */
function runkit_constant_redefine(string $constname, $value) : bool {
}

/**
 * Add a new function, similar to create_function()
 * Gives you more control over the type of function being created
 * (Signature 1 of 2)
 *
 * @param string $funcname Name of function to be created
 * @param string $arglist Comma separated argument list
 * @param string $code Code making up the function
 * @param bool $return_by_reference whether the function should return by reference
 * @param string|null $doc_comment The doc comment of the function
 * @param string|null $return_type Return type of this function (e.g. `stdClass`, `?string`(php 7.1))
 * @return bool - True on success or false on failure.
 */
function runkit_function_add(string $funcname, string $arglist, string $code, bool $return_by_reference = null, string $doc_comment = null, string $return_type = null) : bool {
}

/**
 * Add a new function, similar to create_function()
 * Gives you more control over the type of function being created
 * (Signature 2 of 2)
 *
 * @param string $funcname Name of function to be created
 * @param Closure $closure A closure to use as the source for this function. Static variables and `use` variables are copied.
 * @param string|null $doc_comment The doc comment of the function
 * @return bool - True on success or false on failure.
 */
function runkit_function_add(string $funcname, Closure $closure = null, string $doc_comment = null) : bool {
}

/**
 * Copy a function to a new function name
 * @param string $funcname - Name of existing function
 * @param string $targetname - Name of new function to copy definition to
 * @return bool - True on success or false on failure.
 */
function runkit_function_copy(string $funcname, string $targetname) : bool {
}

/**
 * Replace a function definition with a new implementation. (Should be equivalent to remove() then add())
 * (Signature 1 of 2)
 *
 * @param string $funcname Name of function to redefine
 * @param string $arglist New list of arguments to be accepted by function
 * @param string $code New code implementation
 * @param bool $return_by_reference whether the function should return by reference
 * @param string|null $doc_comment The doc comment of the function
 * @param string|null $return_type Return type of this function (e.g. `stdClass`, `?string`(php 7.1))
 * @return bool - True on success or false on failure.
 */
function runkit_function_redefine(string $funcname, string $arglist, string $code, bool $return_by_reference = null, string $doc_comment = null, string $return_type = null) : bool {
}

/**
 * Replace a function definition with a new implementation. (Should be equivalent to remove() then add())
 * (Signature 2 of 2)
 *
 * @param string $funcname Name of function to redefine
 * @param Closure $closure A closure to use as the new definition for this function. Static variables and `use` variables are copied.
 * @param string|null $doc_comment The doc comment of the function
 * @return bool - True on success or false on failure.
 */
function runkit_function_redefine(string $funcname, Closure $closure = null, string $doc_comment = null) : bool {
}

/**
 * Remove a function definition.
 *
 * @param string $funcname Name of function to be deleted
 * @return bool - True on success or false on failure.
 */
function runkit_function_remove(string $funcname) : bool {
}

/**
 * Change a function's name
 *
 * @param string $funcname Current function name
 * @param string $newname New function name
 *
 * @return bool - True on success or false on failure.
 */
function runkit_function_rename(string $funcname, string $newname) : bool {
}

/**
 * Dynamically adds a new method to a given class
 * (Signature 1 of 2)
 *
 * @param string $classname The class to which this method will be added
 * @param string $methodname The name of the method to add
 * @param string $arglist Comma separated argument list
 * @param string $code The code to be evaluated when $methodname is called
 * @param int $flags The type of method to create, can be RUNKIT_ACC_PUBLIC, RUNKIT_ACC_PROTECTED or RUNKIT_ACC_PRIVATE
 *                   optionally combined via bitwise OR with RUNKIT_ACC_STATIC (since 1.0.1)
 * @param string|null $doc_comment The doc comment of the method
 * @param string|null $return_type Return type of this method (e.g. `stdClass`, `?string`(php 7.1))
 * @return bool - True on success or false on failure.
 */
function runkit_method_add(string $funcname, string $methodname, string $arglist, string $code, int $flags = RUNKIT_ACC_PUBLIC, string $doc_comment = null, string $return_type = null) : bool {
}

/**
 * Add a new function, similar to create_function()
 * Gives you more control over the type of function being created
 * (Signature 2 of 2)
 *
 * @param string $classname The class to which this method will be added
 * @param string $methodname The name of the method to add
 * @param Closure $closure A closure to use as the source for this function. Static variables and `use` variables and return types are copied.
 * @param int $flags The type of method to create, can be RUNKIT_ACC_PUBLIC, RUNKIT_ACC_PROTECTED or RUNKIT_ACC_PRIVATE
 *                   optionally combined via bitwise OR with RUNKIT_ACC_STATIC (since 1.0.1)
 * @param string|null $doc_comment The doc comment of the method
 * @return bool - True on success or false on failure.
 */
function runkit_method_add(string $funcname, string $methodname, Closure $closure, int $flags = RUNKIT_ACC_PUBLIC, string $doc_comment = null) : bool {
}

/**
 * Copies a method from class to another
 * @param string $dClass Destination class for copied method
 * @param string $dMethod Destination method name
 * @param string $sClass Source class of the method to copy
 * @param string $sMethod Name of the method to copy from the source class. If this parameter is omitted, the value of $dMethod is assumed.
 * @return bool - True on success or false on failure.
 */
function runkit_method_copy(string $dClass, string $dMethod, string $sClass, string $sMethod = null) : bool {
}

/**
 * Replace a method definition with a new implementation. (Should be equivalent to remove() then add())
 * (Signature 1 of 2)
 *
 * @param string $classname The class in which to redefine the method
 * @param string $methodname The name of the method to redefine
 * @param string $arglist Comma separated argument list for the redefined method
 * @param string $code The new code to be evaluated when methodname is called
 * @param int $flags The type of method to create, can be RUNKIT_ACC_PUBLIC, RUNKIT_ACC_PROTECTED or RUNKIT_ACC_PRIVATE
 *                   optionally combined via bitwise OR with RUNKIT_ACC_STATIC (since 1.0.1)
 * @param string|null $doc_comment The doc comment of the method
 * @param string|null $return_type Return type of this method (e.g. `stdClass`, `?string`(php 7.1))
 * @return bool - True on success or false on failure.
 */
function runkit_method_redefine(string $funcname, string $methodname, string $args, string $code, int $flags = RUNKIT_ACC_PUBLIC, string $doc_comment = null, string $return_type = null) : bool {
}

/**
 * Replace a method definition with a new implementation. (Should be equivalent to remove() then add())
 * (Signature 2 of 2)
 *
 * @param string $classname The class in which to redefine the method
 * @param string $methodname The name of the method to redefine
 * @param Closure $closure A closure to use as the new definition for this function. Static variables and `use` variables and return types are copied.
 * @param string|null $doc_comment The doc comment of the method
 * @return bool - True on success or false on failure.
 */
function runkit_method_redefine(string $funcname, string $methodname, Closure $closure, $doc_comment = null) : bool {
}

/**
 * Dynamically removes the given method
 * (Signature 2 of 2)
 *
 * @param string $classname The class in which to remove the method
 * @param string $methodname The name of the method to remove
 * @return bool - True on success or false on failure.
 */
function runkit_method_remove(string $funcname, string $methodname) : bool {
}

/**
 * Dynamically changes the name of the given method
 *
 * @param string $classname The class in which to rename the method
 * @param string $methodname The name of the method to rename
 * @param string $newname The new name to give to the renamed method
 * @return bool - True on success or false on failure.
 */
function runkit_method_rename(string $funcname, string $newname) : bool {
}

/**
 * Gets a unique integer identifier (Will be reused when the object is garbage collected) for an object.
 * This is similar to `spl_object_hash`, but returns an int instead of a string.
 *
 * @param object $obj - The object
 * @return int|null - Returns null if given a non-object.
 */
function runkit_object_id($obj) : int {
}

/**
 * Return numerically indexed array of registered superglobals.
 * @return string[]
 */
function runkit_superglobals() : array {
}
