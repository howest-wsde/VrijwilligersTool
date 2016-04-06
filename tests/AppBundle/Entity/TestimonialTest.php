<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Testimonial;

class TestimonialTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {

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
