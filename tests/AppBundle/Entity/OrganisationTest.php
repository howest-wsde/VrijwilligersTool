<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Organisation;

class OrganisationTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {

  }

  public function testCreateOrganisation()
  {
    //test creation of a Organisation, and specifically the validation of all different setters, both for ES and for mysql
    //Name(str, 100), Description(str, 1000), Contact(T), Creator(T), Vacancy(+, T)
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testRetrieveOrganisation()
  {
    //test read operation on a Organisation in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testUpdateOrganisation()
  {
    //test update operation on a Organisation in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  protected function tearDown()
  {

  }
}
