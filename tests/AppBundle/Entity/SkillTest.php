<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Skill;
use Symfony\Component\Validator\Validation;

/**
 * Unit test for the Skill Entity
 * The test focuses on validation of the different properties and the correct
 * retrieval of said properties.
 * Tested properties: Name(String, 50), Id(Int, 10)
 */
class SkillTest extends \PHPUnit_Framework_TestCase
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
   * the dataProvider for testName
   * @return array containing all fringe cases identified @ current
   */
  public function nameProvider()
  {
    return array(
      'normal' => array("klussen", 0),
      'too short' => array("a", 1),
      'too long' => array("This name is by far too long for any skill, right!!", 1),
      'empty' => array("", 1),
      'object' => array(new Skill('jossen'), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Name property (Type: String, maxlength = 50, must be unique).
   * The test creates a Skill, setting its id from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived name equals the set name.
   * There is a second test for the Name property: testNameUnique, just below.
   * @dataProvider nameProvider
   * @param multiple  $name        a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testName($name, $errorCount)
  {
    $skill = new Skill($name);
    $errors = $this->validator->validate($skill);
    $this->assertEquals($name, $skill->getName());
    $this->assertEquals($errorCount, count($errors));
  }

/**
 * A simple test to check whether it's possible to make two Skill objects with
 * the same name.  This should be impossible.
 */
  public function testNameUnique(){
    $name = "vogelen";
    $skill = new Skill($name);
    try {
      $skill2 = new Skill($name);
      $this->assertNull($skill2, 'The second skill was instantiated with the same name as the first: please rectify so that this becomes impossible');
    } catch (Exception $e) {
      $this->assertNull($skill2, "This should be unreachable if skill 2 is not null");
    }
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
      'object' => array(new Skill("varen"), 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Id property (Type: Integer, maxlength = 10, must be unique).
   * The test creates a Skill, setting its id from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived id equals the set id.
   * There is a second test for the Id property: testIdUnique, just below.
   * @dataProvider idProvider
   * @param multiple  $id          a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testId($id, $errorCount)
  {
    $skill = new Skill("kantklossen");
    $skill->setId($id);
    $errors = $this->validator->validate($skill);
    $this->assertEquals($id, $skill->getId());
    $this->assertEquals($errorCount, count($errors));
  }

/**
 * A simple test to check whether it's possible to make two Skill objects with
 * the same id.  This should be impossible.
 */
  public function testIdUnique(){
    $id = 1;
    $skill = new Skill($id);
    try {
      $skill2 = new Skill($id);
      $this->assertNull($skill2, 'The second skill was instantiated with the same id as the first: please rectify so that this becomes impossible');
    } catch (Exception $e) {
      $this->assertNull($skill2, "This should be unreachable if skill 2 is not null");
    }
  }
}
