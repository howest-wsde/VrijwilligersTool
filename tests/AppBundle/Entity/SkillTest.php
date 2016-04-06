<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Skill;

class SkillTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {

  }

  public function testCreateSkill()
  {
    //test creation of a Skill, and specifically the validation of all different setters, both for ES and for mysql
    //Name(str, 50)
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testRetrieveSkill()
  {
    //test read operation on a Skill in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testUpdateSkill()
  {
    //test update operation on a Skill in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  protected function tearDown()
  {

  }
}
