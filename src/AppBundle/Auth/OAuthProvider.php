<?php
namespace AppBundle\Auth;
 
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use AppBundle\Entity\Person;
 
class OAuthProvider extends OAuthUserProvider
{
    protected $session, $doctrine, $admins;
 
    public function __construct($session, $doctrine, $service_container)
    {
        $this->session = $session;
        $this->doctrine = $doctrine;
        $this->container = $service_container;
    }
 
    public function loadUserByUsername($username)
    {
        /*
        $qb = $this->doctrine->getManager()->createQueryBuilder();
        $qb->select('u')
            ->from('FoggylineTickerBundle:User', 'u')
            ->where('u.googleId = :gid')
            ->setParameter('gid', $username)
            ->setMaxResults(1);
        $result = $qb->getQuery()->getResult();
 
        if (count($result)) {
            return $result[0];
        } else {
            return new User();
        }
        */
    }
 
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        //Data from Google response
        $google_id = $response->getUsername(); /* An ID like: 112259658235204980084 */
        $email = $response->getEmail();
        $nickname = $response->getNickname();
        $realname = $response->getRealName();
        $avatar = $response->getProfilePicture();
 
  $this->session->set('temp', $response);

        //set data in session
        $this->session->set('email', $email);
        $this->session->set('nickname', $nickname);
        $this->session->set('realname', $realname);
        $this->session->set('avatar', $avatar);
 
        //Check if this Google user already exists in our app DB
 
        $qb = $this->doctrine->getManager()->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle:Person', 'u')
            ->where('u.email = :gmail')
            ->setParameter('gmail', $email)
            ->setMaxResults(1);
        $result = $qb->getQuery()->getResult();
 
        //add to database if doesn't exists
        if (!count($result)) {
            $person = new Person(); 
            $person->setFirstname($realname);
            $person->setUsername($nickname);
            $person->setEmail($email);
            //$user->setRoles('ROLE_USER');
 
            //Set some wild random pass since its irrelevant, this is Google login
            $factory = $this->container->get('security.encoder_factory');
            $encoder = $factory->getEncoder($person);
            $password = $encoder->encodePassword(md5(uniqid()), $person->getSalt());
            $person->setPassword($password);
 
            $em = $this->doctrine->getManager();
            $em->persist($person);
            $em->flush();
        } else {
            $user = $result[0];  
        }
 
        //set id
        $this->session->set('id', $person->getId());
 
        return $this->loadUserByUsername($response->getUsername());
    }
}