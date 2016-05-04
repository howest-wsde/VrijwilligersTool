<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\Volunteer;
use AppBundle\Entity\Form\UserType;

class SecurityControllerTest extends \WebTestCase
{
    public function testRegisterAction()
    {
      // $this->assertEquals(200, $client->getResponse()->getStatusCode());
      // $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }

    public function testLoginAction()
    {
      $this->markTestIncomplete("deze test werd nog niet geïmplementeerd");
    }

}
