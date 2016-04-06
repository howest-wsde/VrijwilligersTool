<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\SkillProficiency;

class SkillProficiencyTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {

  }

  public function testCreateSkillProficiency()
  {
    //test creation of a SkillProficiency, and specifically the validation of all different setters, both for ES and for mysql
    //Type(T), Proficiency(int, 5 = score op 10)
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testRetrieveSkillProficiency()
  {
    //test read operation on a SkillProficiency in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testUpdateSkillProficiency()
  {
    //test update operation on a SkillProficiency in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  protected function tearDown()
  {

  }
}
