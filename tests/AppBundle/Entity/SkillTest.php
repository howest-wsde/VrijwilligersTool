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
    $this->markTestIncomplete("test that this name is unique, and also add that test to Organisation");

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
   * @dataProvider nameProvider
   * @param multiple  $id          a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testId($id, $errorCount)
  {
    $this->markTestIncomplete("Skill has no setId method atm, so this test cannot be run currently");
    $skill = new Skill("kantklossen");
    $skill->setId($id);
    $errors = $this->validator->validate($skill);
    $this->assertEquals($id, $skill->getId());
    $this->assertEquals($errorCount, count($errors));
    $this->markTestIncomplete("test that this id is unique, and also copy this test to any other entity that has an id");
  }
}
