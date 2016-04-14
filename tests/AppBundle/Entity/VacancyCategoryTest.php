<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\VacancyCategory;
use Symfony\Component\Validator\Validation;

/**
 * Unit test for the Vacancy Entity
 * The test focuses on validation of the different properties and the correct
 * retrieval of said properties.
 * Tested properties: id(int, 10), name(str, 100)
 */
class VacancyCategoryTest extends \PHPUnit_Framework_TestCase
{
  /**
   * A Symfony validator allowing to check the in-built Symfony validation rules as they apply
   * to the tested entity
   * @var Symfony\Component\Validator\Validator\RecursiveValidator
   */
  public $validator;

  protected function setUp()
  {
    $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
  }

  /**
   * the dataProvider for testId
   * @return array containing all fringe cases identified @ current
   */
  public function idProvider()
  {
    return array(
      'normal' => array(1, 0),
      'empty' => array("", 1),
      'object' => array(new VacancyCategory(), 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Id property (Type: Integer, maxlength = 10, must be unique).
   * The test creates a VacancyCategory, setting its id from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived id equals the set id.
   * There is a second test for the Id property: testIdUnique, just below.
   * @dataProvider idProvider
   * @param multiple  $id          a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testId($id, $errorCount)
  {
    $vacancyCategory = new VacancyCategory();
    $vacancyCategory->setId($id);
    $errors = $this->validator->validate($vacancyCategory);
    $this->assertEquals($id, $vacancyCategory->getId());
    $this->assertEquals($errorCount, count($errors));
  }

/**
 * A simple test to check whether it's possible to make two VacancyCategory objects with
 * the same id.  This should be impossible.
 */
  public function testIdUnique(){
    $id = 1;
    $vacancyCategory = new VacancyCategory();
    $vacancyCategory->setId($id);
    try {
      $vacancyCategory2 = new VacancyCategory();
      $vacancyCategory2->setId($id);
      $this->assertNull($vacancyCategory2, 'The second vacancyCategory was instantiated with the same id as the first: please rectify so that this becomes impossible');
    } catch (Exception $e) {
      $this->assertNull($vacancyCategory2, "This should be unreachable if vacancyCategory 2 is not null");
    }
  }

  /**
   * the dataProvider for testName
   * @return array containing all fringe cases identified @ current
   */
  public function nameProvider()
  {
    return array(
      'normal' => array("Wereldwinkel Roeselare", 0),
      'too short' => array("a", 1),
      'too long' => array("This name is by far too long for any organisation, right!  I mean seriously, what are they thinking?!", 1),
      'empty' => array("", 1),
      'object' => array(new VacancyCategory(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Name property (Type: String, maxlength = 100, must be unique).
   * The test creates an VacancyCategory, setting its name from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived name equals the set name.
   * There is a second test for the Name property: testNameUnique, just below.
   * @dataProvider nameProvider
   * @param multiple  $name        a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testName($name, $errorCount)
  {
    $vacancyCategory = new VacancyCategory();
    $vacancyCategory->setName($name);
    $errors = $this->validator->validate($vacancyCategory);
    $this->assertEquals($name, $vacancyCategory->getName());
    $this->assertEquals($errorCount, count($errors));
  }

/**
 * A simple test to check whether it's possible to make two VacancyCategory objects with
 * the same name.  This should be impossible.
 */
  public function testNameUnique(){
    $name = "vogelen";
    $vacancyCategory = new VacancyCategory();
    $vacancyCategory->setName($name);
    try {
      $vacancyCategory2 = new VacancyCategory();
      $vacancyCategory2->setName($name);
      $this->assertNull($vacancyCategory2, 'The second vacancyCategory was instantiated with the same name as the first: please rectify so that this becomes impossible');
    } catch (Exception $e) {
      $this->assertNull($vacancyCategory2, "This should be unreachable if vacancyCategory 2 is not null");
    }
  }
}
