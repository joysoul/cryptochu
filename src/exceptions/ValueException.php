<?php
namespace cryptochu\exceptions;

use Exception;

/**
 * ValueException is thrown if a value has an unexpected, invalid or otherwise incorrect value.
 * Note that if the type is incorrect, a TypeException should be thrown instead.
 *
 * @author Emile Pels
 * @package cryptochu\exceptions
 */
class ValueException extends Exception
{

}
