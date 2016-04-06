
<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Volunteer;

class VolunteerTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {

  }

  public function testCreateVolunteer()
  {
    //test creation of a Volunteer, and specifically the validation of all different setters, both for ES and for mysql
    //Firstname(str, 100), Lastname(str, 100), Username(str, 150), Passphrase(str, 60), Contact(T), Organisation(+, T), Skillproficiency(+, T), Testimonial(+, T)
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testRetrieveVolunteer()
  {
    //test read operation on a Volunteer in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testUpdateVolunteer()
  {
    //test update operation on a Volunteer in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  protected function tearDown()
  {

  }
}
