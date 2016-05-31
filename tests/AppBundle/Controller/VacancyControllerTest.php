<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Vacancy;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PersonControllerTest extends WebTestCase
{

    /**
     * [$client description]
     * @var null
     */
    private $client = null;

     /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em = null;

      /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->client = static::createClient();
    }

    /**
     * Testing the createPdfAction Controller to see if:
     * 1. given a valid urlid a pdf is created
     * 2. given an unvalid urlid meaningfull feedback is given to the client
     * Route for the controller: /vacature/pdf/{urlid}
     */
    public function testCreatePdfAction(){

    }

    /**
     *
     * /vacature/start
     */
    public function testStartVacancyAction(){

    }

    /**
     *
     * /vacature/nieuw && /{organisation_urlid}/vacature/nieuw
     */
    public function testCreateVacancyAction(){

    }

    /**
     *
     * /vacature/{urlid}
     */
    public function testVacancyViewAction(){

    }

    /**
     *
     * /vacature/{urlid}/inschrijven
     */
    public function testSubscribeVacancyAction(){

    }

    /**
     *
     * /vacature/{urlid}/{likeunlike}
     */
    public function testLikeVacancyAction(){

    }

    /**
     *
     * /vacature/aanpassen/{urlid}
     */
    public function testEditVacancyAction(){

    }

    /**
     *
     *
     */
    public function testListRecentVacanciesAction(){

    }

    /**
     *
     *
     */
    public function testListParentSkillsAction(){

    }

     /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

    /**
     * Cookbook function to log in a user with a given role so that routes can be tested that require a logged in user of a given role
     */
    private function logIn($user, $role = 'ROLE_USER')
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';
        $username = 'beuntje';

        $token = new UsernamePasswordToken('beuntje', $user->getPassword(), $firewall, array($role));
        $session->set('_security_'.$firewall, serialize($token));
        $session->set('user', $user); //todo: delete after slashdot post
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
