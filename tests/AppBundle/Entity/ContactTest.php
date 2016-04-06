<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Contact;

class ContactTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {

  }

  public function testCreateContact()
  {
    //test creation of a Contact, and specifically the validation of all different setters, both for ES and for mysql
    //Email(str, 255), Address(str, 255), Telephone(str, 10) => onvoldoende voor internationale nummers
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testRetrieveContact()
  {
    //test read operation on a Contact in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testUpdateContact()
  {
    //test update operation on a Contact in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  protected function tearDown()
  {

  }
}
