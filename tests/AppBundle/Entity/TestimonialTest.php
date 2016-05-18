<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Testimonial;
use AppBundle\Entity\Person;
use Symfony\Component\Validator\Validation;

/**
 * Unit test for the Vacancy Entity
 * The test focuses on validation of the different properties and the correct
 * retrieval of said properties.
 * Tested properties: id(int, 10), value(str, 2000), sender(T, 10),
 * receiver(T, 10), approved (boolean)
 */
class TestimonialTest extends \PHPUnit_Framework_TestCase
{
  /**
   * A Symfony validator allowing to check the in-built Symfony validation rules as they apply
   * to the tested entity
   * @var Symfony\Component\Validator\Validator\RecursiveValidator
   */
  public $validator;
  public $baseTestimonial;

  protected function setUp()
  {
    $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    $this->baseTestimonial = new Testimonial();
    $this->baseTestimonial = $this->baseTestimonial->setId(1)->setValue("dit is een korte testimonial");
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
      'object' => array(array(), 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Id property (Type: Integer, maxlength = 10, must be unique).
   * The test creates a Testimonial, setting its id from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived id equals the set id.
   * There is a second test for the Id property: testIdUnique, just below.
   * @dataProvider idProvider
   * @param multiple  $id          a value from the fringe cases array
   * @param integer   $errorCount  the expected amount of errors
   */
  public function testId($id, $errorCount)
  {
    // $this->markTestIncomplete("testing one property at the time");
    try {
      $testimonial = $this->baseTestimonial;
      $testimonial->setId($id);
      $errors = $this->validator->validate($testimonial);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($id, $testimonial->getId());
    $this->assertEquals($errorCount, count($errors));
  }

/**
 * A simple test to check whether it's possible to make two Testimonial objects with
 * the same id.  This should be impossible.
 */
  public function testIdUnique(){
    $this->markTestIncomplete("some problem with the unique validator");
    $testimonial = clone $this->baseTestimonial;
    try {
      $testimonial2 = clone $this->baseTestimonial;
      $errors = $this->validator->validate($testimonial2);
    } catch (Exception $e) {
      //this is here mainly to sanitize output
    }
    $this->assertGreaterThan(0, $errors, 'testimonial 2 should have at least one validation error as the id is not unique');
  }

  /**
   * the dataProvider for testValue
   * @return array containing all fringe cases identified @ current
   */
  public function valueProvider()
  {
    return array(
      'normal' => array("This is a test value that's neither too long nor too short, thus detailing exactly how awesome this person or organisation is.", 0),
      'too short' => array("too short", 1),
      'too long' => array("Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et do", 1),
      'empty' => array('', 1),
      'object' => array(array(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Value property (Type: String, maxlength = 2000).
   * The test creates a Testimonial, setting its value from an
   * array of fringe cases, then checking whether there are validation errors and
   * whether the retreived value equals the set value.
   * @dataProvider valueProvider
   * @param multiple  $value        a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testValue($value, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $testimonial = $this->baseTestimonial;
      $testimonial->setValue($value);
      $errors = $this->validator->validate($testimonial);
    } catch(Exception $e){
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($value, $testimonial->getValue());
    $this->assertEquals($errorCount, count($errors));
  }



  /**
   * the dataProvider for testSender
   * @return array containing all fringe cases identified @ current
   */
  public function senderProvider()
  {
    return array(
      'normal' => array(new Person(), 0),
      'empty' => array("", 1),
      'object' => array(array(), 1),
      'numeric' => array(10, 1),
    );
  }

  /**
   * Test case for the Sender property (Type: \AppBundle\Entity\Person)
   * The test creates an Testimonial, setting its sender from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived sender equals the set sender.
   * @dataProvider senderProvider
   * @param multiple  $sender      a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testSender($sender, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $testimonial = $this->baseTestimonial;
      $testimonial->setSender($sender);
      $errors = $this->validator->validate($testimonial);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($sender, $testimonial->getSender());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataProvider for testReceiver
   * @return array containing all fringe cases identified @ current
   */
  public function receiverProvider()
  {
    return array(
      'normal' => array(new Person(), 0),
      'empty' => array("", 1),
      'object' => array(array(), 1),
      'numeric' => array(10, 1),
    );
  }

  /**
   * Test case for the Receiver property (Type: \AppBundle\Entity\Person)
   * The test creates an Testimonial, setting its receiver from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived receiver equals the set receiver.
   * @dataProvider receiverProvider
   * @param multiple  $receiver      a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testReceiver($receiver, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $testimonial = $this->baseTestimonial;
      $testimonial->setReceiver($receiver);
      $errors = $this->validator->validate($testimonial);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($receiver, $testimonial->getReceiver());
    $this->assertEquals($errorCount, count($errors));
  }

  /**
   * the dataProvider for testApproved
   * @return array containing all fringe cases identified @ current
   */
  public function approvedProvider()
  {
    return array(
      'normal' => array(true, 0),
      'empty' => array("", 1),
      'object' => array(array(), 1),
      'numeric' => array(10, 1),
    );
  }

  /**
   * Test case for the Approved property (Type: Boolean)
   * The test creates an Testimonial, setting its approved status from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived approved status equals the set approved status.
   * @dataProvider receiverProvider
   * @param multiple  $approved     a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testApproved($approved, $errorCount)
  {
    $this->markTestIncomplete("testing one property at the time");
    try {
      $testimonial = $this->baseTestimonial;
      $testimonial->setApproved($approved);
      $errors = $this->validator->validate($testimonial);
    } catch (Exception $e) {
      //nothing needs to be done, this is mainly to sanitize output
    }
    $this->assertEquals($approved, $testimonial->getApproved());
    $this->assertEquals($errorCount, count($errors));
  }
}
