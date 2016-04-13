<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Testimonial;
use Symfony\Component\Validator\Validation;

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

  public function testCreateTestimonial()
  {
    //test creation of a Testimonial, and specifically the validation of all different setters, both for ES and for mysql
    //Sender(T), Receiver(T), Value(str, 2000)
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testRetrieveTestimonial()
  {
    //test read operation on a Testimonial in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testUpdateTestimonial()
  {
    //test update operation on a Testimonial in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  protected function tearDown()
  {

  }
}
