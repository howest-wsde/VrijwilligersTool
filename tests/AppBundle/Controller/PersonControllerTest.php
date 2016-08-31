<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Person;
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
     * Testing the personViewAction Controller with a valid username that should be present in the dbase due to the dummy data.
     * Condition tested is:
     * 1. if the user is not logged in, does the controller redirect to the login form?
     */
    public function testPersonViewActionWithoutLogin()
    {
      $username = 'beuntje';
      $client = $this->client;
      $client->request('GET', '/persoon/' . $username);

      $this->assertTrue($client->getResponse()->isRedirect());
      $targetUrl = $client->getResponse()->getTargetUrl();
      $this->assertRegExp('/\/login$/', $targetUrl);
    }

    /**
     * Testing the personViewAction Controller with a valid username that should be present in the dbase due to the dummy data.
     * Conditions tested are:
     * 1. does a response get rendered or not
     * 2. does the response contain the correct title
     * 3. does the response contain the expected information
     */
    public function testPersonViewActionWithUsername()
    {
      //TODO: deze controller bespreken met Benedikt.  Niet zeker of hier wel de juiste template gemaakt werd?
      $username = 'beuntje';
      $user = $this->em->getRepository('AppBundle\Entity\Person')
        ->findOneByUsername($username);

      $client = $this->client;
      $this->logIn($user);
      $crawler = $client->request('GET', '/persoon/' . $username);


      //needs logged in user, redirects to /login
      $this->assertTrue($client->getResponse()->isSuccessful());

      $this->assertContains('Vrijwilliger', $crawler->filter('title')->text());
      $this->assertContains($user->getFullName(), $crawler->filter('body')->text());
      $this->assertContains($user->getLastName(), $crawler->filter('body')->text());
      $this->assertContains($user->getFirstName(), $crawler->filter('body')->text());
      $this->assertContains($user->getUserName(), $crawler->filter('body')->text());
    }

    /**
     * Testing the personViewAction Controller with a valid username that should be present in the dbase due to the dummy data.
     * Condition tested is:
     * 1. if no username is added, does the controller return a not found error?
     */
    public function testPersonViewActionWithoutUsername()
    {
      $client = $this->client;
      $client->request('GET', '/persoon/');

      $this->assertTrue($client->getResponse()->isNotFound());
    }

    /**
     * Testing the selfAction Controller without logging in first
     * Condition tested is:
     * 1. if no user is logged in, does the controller redirect to the login form?
     */
    public function testSelfActionWithoutLogin()
    {
      $client = $this->client;
      $client->request('GET', '/persoon');

      $this->assertTrue($client->getResponse()->isRedirect());
      $targetUrl = $client->getResponse()->getTargetUrl();
      $this->assertRegExp('/\/login$/', $targetUrl);
    }

    /**
     * Testing the selfA$regexp = tion Controller afte\ro . $user->getUserName() . ' l'gging in;'
     * Condition tested is:
     * 1. if a user is logged in, does the controller redirect to the person_username function for the user?
     */
    public function testSelfActionWithLogin()
    {
      // $this->markTestIncomplete("deze is nog niet af, er is een error bij het instellen van de user waardoor op lijn 39 in PersonController geen username kan gevonden worden.");
      $username = 'beuntje';
      $user = $this->em->getRepository('AppBundle\Entity\Person')
        ->findOneByUsername($username);
      $this->logIn($user);
      $this->assertEquals($username, $user->getUserName());

      $client = $this->client;
      $client->request('GET', '/persoon', array(), array(), array('PHP_AUTH_USER  ' => $user));//TODO evaluate everything past get

      $this->assertTrue($client->getResponse()->isRedirect());
      $targetUrl = $client->getResponse()->getTargetUrl();
      $regexp = '/\/persoon\/' . $user->getUserName() . '$/';
      $this->assertRegExp($regexp, $targetUrl);
    }

    public function testListRecentPersonsAction()
    {
      $this->markTestIncomplete("deze route wordt momenteel niet gebruikt, de test werd dan ook nog niet aangemaakt");
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
        $this->get('security.token_storage')->setToken($token);
        $session->set('user', $user); //todo: delete after slashdot post
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
