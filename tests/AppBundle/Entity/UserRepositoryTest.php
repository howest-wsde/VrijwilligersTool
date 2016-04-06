?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\UserRepository;

class UserRepositoryTest extends PHPUnit_Framework_TestCase
{
  protected function setUp()
  {

  }

  public function testCreateUserRepository()
  {
    //test creation of a UserRepository
    //checken bij Jelle wat dit doet
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testRetrieveUserRepository()
  {
    //test read operation on a UserRepository in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  public function testUpdateUserRepository()
  {
    //test update operation on a UserRepository in the dbase, both for ES and for mysql
    $this->markTestIncomplete('deze test werd nog niet uitgewerkt');
  }

  protected function tearDown()
  {

  }
}
