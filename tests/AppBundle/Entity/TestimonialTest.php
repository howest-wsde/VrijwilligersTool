<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Testimonial;
use AppBundle\Entity\Volunteer;
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
      'object' => array(new Testimonial(), 1),
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
    $testimonial = new Testimonial();
    $testimonial->setId($id);
    $errors = $this->validator->validate($testimonial);
    $this->assertEquals($id, $testimonial->getId());
    $this->assertEquals($errorCount, count($errors));
  }

/**
 * A simple test to check whether it's possible to make two Testimonial objects with
 * the same id.  This should be impossible.
 */
  public function testIdUnique(){
    $id = 1;
    $testimonial = new Testimonial();
    $testimonial->setId($id);
    try {
      $testimonial2 = new Testimonial();
      $testimonial2->setId($id);
      $this->assertNull($testimonial2, 'The second testimonial was instantiated with the same id as the first: please rectify so that this becomes impossible');
    } catch (Exception $e) {
      $this->assertNull($testimonial2, "This should be unreachable if testimonial 2 is not null");
    }
  }

  //TODO rename the class to Person once refactored in that way.
  /**
   * the dataProvider for testSender
   * @return array containing all fringe cases identified @ current
   */
  public function senderProvider()
  {
    return array(
      'normal' => array(new Volunteer(), 0),
      'empty' => array("", 1),
      'object' => array(new Testimonial(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Sender property (Type: \AppBundle\Entity\Volunteer)
   * The test creates an Testimonial, setting its sender from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived sender equals the set sender.
   * @dataProvider senderProvider
   * @param multiple  $sender      a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testSender($sender, $errorCount)
  {
    $this->markTestIncomplete("this test hasn't been completed yet");
    $testimonial = new Testimonial();
    $testimonial->setSender($sender);
    $errors = $this->validator->validate($testimonial);
    $this->assertEquals($sender, $testimonial->getSender());
    $this->assertEquals($errorCount, count($errors));
  }

  //TODO rename the class to Person once refactored in that way.
  /**
   * the dataProvider for testReceiver
   * @return array containing all fringe cases identified @ current
   */
  public function receiverProvider()
  {
    return array(
      'normal' => array(new Volunteer(), 0),
      'empty' => array("", 1),
      'object' => array(new Testimonial(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
    );
  }

  /**
   * Test case for the Receiver property (Type: \AppBundle\Entity\Volunteer)
   * The test creates an Testimonial, setting its receiver from an array of
   * fringe cases, then checking whether there are validation errors and whether the
   * retreived receiver equals the set receiver.
   * @dataProvider receiverProvider
   * @param multiple  $receiver      a value from the fringe cases array
   * @param integer   $errorCount   the expected amount of errors
   */
  public function testReceiver($receiver, $errorCount)
  {
    $this->markTestIncomplete("this test hasn't been completed yet");
    $testimonial = new Testimonial();
    $testimonial->setReceiver($receiver);
    $errors = $this->validator->validate($testimonial);
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
      'object' => array(new Testimonial(), 1),
      'numeric' => array(10, 1),
      'null' => array(null, 1),
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
    $this->markTestIncomplete("this test hasn't been completed yet");
    $testimonial = new Testimonial();
    $testimonial->setApproved($approved);
    $errors = $this->validator->validate($testimonial);
    $this->assertEquals($approved, $testimonial->getApproved());
    $this->assertEquals($errorCount, count($errors));
  }
}
