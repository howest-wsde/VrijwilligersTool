<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\VacancyCategory;

class VacancyCategoryTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {

  }

  public function testCreateVacancyCategory()
  {
    //test creation of a VacancyCategory, and specifically the validation of all different setters, both for ES and for mysql
    //Name(str, 100)
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testRetrieveVacancyCategory()
  {
    //test read operation on a VacancyCategory in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testUpdateVacancyCategory()
  {
    //test update operation on a VacancyCategory in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  protected function tearDown()
  {

  }
}
