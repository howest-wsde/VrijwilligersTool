<?php

namespace AppBundle\Utils;

class Random
{
  /**
   * Faster alternative to get an array containing only unique values.  Precondition: the array should only contain strings or integers.  It works by flipping the array, removing the doubles, then taking the keys and returning those as the new array.
   * @param  array $array an array to be unique-icized
   * @return array        an array containing only unique numbers/strings
   */
  function uniqueArrayValues($array){
    return array_keys(array_flip($array));
  }

/**
 * Generate an array containing random integer values
 * @param  integer $min         the lowest possible value in the array
 * @param  integer $max         the highest possible value in the array
 * @param  integer $amount      the number of values in the array
 * @param  integer $contingency an offset to ensure there's enough unique values in the array.  Standard value is 10, this should be higher if the spread is very big.
 * @return array                array of random unique integers
 */
  function generateRandomUniqueIntegerArray($min, $max, $amount, $contingency = 10){
    $array = array();
    for ($i = 0; $i < ($amount + $contingency); $i++) {
      $array[] = mt_rand($min, $max);
    }

    return array_slice($this->uniqueArrayValues($array), 0, $amount);
  }
}
