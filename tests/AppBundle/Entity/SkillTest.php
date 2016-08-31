<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Skill;
use Symfony\Component\Validator\Validation;

/**
 * Unit test for the Skill Entity
 * The test focuses on validation of the different properties and the correct
 * retrieval of said properties.
 * Tested properties: Name(String, 50), Id(Int, 10), ParentId(Int, 10)
 */
class SkillTest extends \PHPUnit_Framework_TestCase
{
  /**
   * A Symfony validator allowing to check the in-built Symfony validation rules as they apply
   * to the tested entity
   * @var Symfony\Component\Validator\Validator\RecursiveValidator
   */
  public $validator;
  public $baseSkill;

  protected function setUp()
  {
    $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    $this->baseSkill = new Skill("jossen");
    $this->baseSkill = $this->baseSkill->setId(1);
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
      'object' => array(array(), 1),
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
    // $this->markTestIncomplete("testing one property at the time");
    try {
      $skill = $this->baseSkill;
      $skill->setName($name);
      $errors = $this->validator->validate($skill);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($name, $skill->getName());
    $this->assertEquals($errorCount, count($errors));
  }

/**
 * A simple test to check whether it's possible to make two Skill objects with
 * the same name.  This should be impossible.
 */
  public function testNameUnique(){
    $this->markTestIncomplete("some problem with the unique validator");
    $skill = clone $this->baseSkill;
    try {
      $skill2 = clone $this->baseSkill;
      $errors = $this->validator->validate($skill2);
    } catch (Exception $e) {
      //this is here mainly to sanitize output
    }
    $this->assertGreaterThan(0, $errors, 'Skill 2 should have at least one validation error as the name is not unique');
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
    $this->markTestIncomplete("testing one property at the time");
    try {
      $skill = $this->baseSkill;
      $skill->setId($id);
      $errors = $this->validator->validate($skill);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($id, $skill->getId());
    $this->assertEquals($errorCount, count($errors));
  }

/**
 * A simple test to check whether it's possible to make two Skill objects with
 * the same id.  This should be impossible.
 */
  public function testIdUnique(){
    $this->markTestIncomplete("some problem with the unique validator");
    $skill = clone $this->baseSkill;
    try {
      $skill2 = clone $this->baseSkill;
      $errors = $this->validator->validate($skill2);
    } catch (Exception $e) {
      //this is here mainly to sanitize output
    }
    $this->assertGreaterThan(0, $errors, 'Skill 2 should have at least one validation error as the id is not unique');  }

  /**
   * the dataProvider for testParentId
   * @return array containing all fringe cases identified @ current
   */
  public function parentIdProvider()
  {
    return array(
      'normal' => array(1, 0),
      'empty' => array("", 1),
      'object' => array(new Skill("varen"), 1),
      'null' => array(null, 0),
    );
  }

  /**
   * Test case for the ParentId property (Type: Integer, maxlength = 10, must be unique).
   * The test creates a Skill, setting its parentId from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived id equals the set id.
   * @dataProvider parentIdProvider
   * @param multiple  $parentId    a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testParentId($parentId, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $skill = $this->baseSkill;
      $skill->setId($parentId);
      $errors = $this->validator->validate($skill);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($parentId, $skill->getId());
    $this->assertEquals($errorCount, count($errors));
  }
}
