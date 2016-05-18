<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Form\UserRepository")
 *
 * @UniqueEntity(fields = "username", message = "person.username.already_used")
 * @UniqueEntity(fields = "email", message = "person.email.already_used")
 * @UniqueEntity(fields = "telephone", message = "person.telephone.already_used")
 *
 * @Assert\Callback({"AppBundle\Entity\Person", "validate_email_and_telephone"})
 */
class Person extends EntityBase implements UserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message = "person.not_blank")
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "person.min_message_one",
     *      maxMessage = "person.max_message"
     * )
    */
    private $firstname;

    /**
     * @var string
     * @Assert\NotBlank(message = "person.not_blank")
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "person.min_message_one",
     *      maxMessage = "person.max_message"
     * )
    */
    private $lastname;

    /**
     * @var string
     * @Assert\NotBlank(message = "person.not_blank")
     * @Assert\Length(
     *      min = 2,
     *      max = 150,
     *      minMessage = "person.min_message",
     *      maxMessage = "person.max_message"
     * )
     * @Assert\Regex(
     *     pattern = "/^[^ \/]+$/",
     *     message = "geen spaties of slashes"
     * )
    */
    private $username;

    /**
     * @var string
     */
    private $passphrase;

    /**
     * @var string
     * @Assert\Email(
     *     message = "person.email.valid",
     *     checkHost = true
     * )
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 10,
     *      max = 4096,
     *      minMessage = "person.min_message",
     *      maxMessage = "person.max_message"
     * )
     */
    private $plainPassword;

    /**
     * @var string
     * @Assert\NotBlank(message = "person.not_blank")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "organisation.max_message"
     * )
     */
    private $street;

    /**
     * @var int
     * @Assert\Regex(
     *     pattern = "/^[0-9]*$/",
     *     message="person.not_numeric"
     * )
     * @Assert\Range(
     *      min = 0,
     *      max = 999999,
     *      minMessage = "person.not_positive"
     * )
     */
    private $number;

    /**
     * @var int
     * @Assert\Length(
     * 		min = 1,
     *      max = 6,
     *      minMessage = "person.min_message_one",
     *      maxMessage = "person.max_message"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9]{1,6}$/",
     *     message="person.bus.valid"
     * )
     */
    private $bus;

    /**
     * @var int
     * @Assert\Regex(
     *     pattern = "/^[0-9]*$/",
     *     message="person.not_numeric"
     * )
     * @Assert\Range(
     *      min = 1000,
     *      max = 9999,
     *      minMessage = "person.not_positive",
     *      maxMessage = "not_more_than"
     * )
     * @Assert\Length(
     *      min = 4,
     *      max = 4,
     *      exactMessage = "person.exact"
     * )
     */
    private $postalcode;

    /**
     * @var string
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "person.min_message",
     *      maxMessage = "person.max_message"
     * )
     */
    private $city;

    /**
     * @var string
     * assert callback statement for telephone at top of class
     */
    private $telephone;

    /**
     * Callback that check if either the email or telephone fields are valid
     */
    public static function validate_email_and_telephone($org, ExecutionContextInterface  $context)
    {
        $fields = 0;
        if ($org->getTelephone())
        {
            $fields++;

            $telephone = str_replace(' ', '', $org->getTelephone());

            if (!ctype_digit($telephone)
            or !strlen($telephone) == 10)
            {
                $context->buildViolation("person.telephone.valid")
                    ->atPath("telephone")
                    ->addViolation();
            }
        }
        if ($org->getEmail())
        {
            $fields++;

            // other validators are enabled with annotations
        }

        if ($fields <= 0)
        {
            $context->buildViolation("person.one_of_both")
                ->atPath("telephone")
                ->addViolation();
            $context->buildViolation("person.one_of_both")
                ->atPath("email")
                ->addViolation();
        }
    }

    /**
     * @var string
     * @Assert\Url(
     *    message = "person.linkedin.valid",
     *    protocols = {"http", "https"},
     *    checkDNS = true,
     *    dnsMessage = "person.linkedin.valid"
     * )
     * @Assert\Regex(
     *     pattern = "/\blinkedin.com\b/",
     *     message = "person.linkedin.valid"
     * )
     */
    private $linkedinUrl;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $skills;

    private $isActive;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $testimonials;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $candidacies;

    /**
     * @var \AppBundle\Entity\Organisation
     */
    private $organisation;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skill = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isActive = true;
    }

    public function getFullName()
    {
        return $this->firstname." ".$this->lastname;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getSalt()
    {
        // The bcrypt algorithm doesn't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function getRoles()
    {
        $roles = array("ROLE_USER");
        if (!is_null($this->organisation))
        {
            array_push($roles, "ROLE_ORGANISATION");
        }
        return $roles;
    }

    public function eraseCredentials()
    {
        return null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->passphrase,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->passphrase,
        ) = unserialize($serialized);
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Person
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Person
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Person
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set passphrase
     *
     * @param string $passphrase
     *
     * @return Person
     */
    public function setPassword($passphrase)
    {
        $this->passphrase = $passphrase;

        return $this;
    }

    /**
     * Get passphrase
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->passphrase;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @return Person
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Add skils
     *
     * @param \AppBundle\Entity\skill $skill
     *
     * @return Person
     */
    public function addSkill(\AppBundle\Entity\Skill $skill)
    {
        $this->skills[] = $skill;

        return $this;
    }

    /**
     * Remove skill
     *
     * @param \AppBundle\Entity\Skill $skill
     *
     * @return Person
     */
    public function removeSkill(\AppBundle\Entity\Skill $skill)
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Add testimonial
     *
     * @param \AppBundle\Entity\Testimonial $testimonial
     *
     * @return Person
     */
    public function addTestimonial(\AppBundle\Entity\Testimonial $testimonial)
    {
        $this->testimonials[] = $testimonial;

        return $this;
    }

    /**
     * Remove testimonial
     *
      * @param \AppBundle\Entity\Testimonial $testimonial
     *
     * @return Person
     */
    public function removeTestimonial(\AppBundle\Entity\Testimonial $testimonial)
    {
        $this->testimonials->removeElement($testimonial);

        return $this;
    }

    /**
     * Get testimonials
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTestimonials()
    {
        return $this->testimonials;
    }

    /**
     * Add candidacy
     *
     * @param \AppBundle\Entity\Candidacy $candidacy
     *
     * @return Person
     */
    public function addCandidacy(\AppBundle\Entity\Candidacy $candidacy)
    {
        $this->candidacies[] = $candidacy;

        return $this;
    }

    /**
     * Remove candidacy
     *
      * @param \AppBundle\Entity\Candidacy $candidacy
     *
     * @return Person
     */
    public function removeCandidacy(\AppBundle\Entity\Candidacy $candidacy)
    {
        $this->candidacies->removeElement($candidacy);

        return $this;
    }

    /**
     * Get candidacies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCandidacies()
    {
        return $this->candidacies;
    }

    /**
     * Set passphrase
     *
     * @param string $passphrase
     *
     * @return Person
     */
    public function setPassphrase($passphrase)
    {
        $this->passphrase = $passphrase;

        return $this;
    }

    /**
     * Get passphrase
     *
     * @return string
     */
    public function getPassphrase()
    {
        return $this->passphrase;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Person
     */
    public function setTelephone($telephone)
    {
        $this->telephone = preg_replace("/\D/", "", $telephone);

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Person
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return Person
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set postalcode
     *
     * @param integer $postalcode
     *
     * @return Person
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    /**
     * Get postalcode
     *
     * @return integer
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Person
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return json_encode( array(
            "Entity" => $this->getClassName(),
            "Id" => $this->getId(),
            "Values" => array(
                "Firstname" => $this->getFirstname(),
                "Lastname" => $this->getLastname(),
                "Username" => $this->getUsername(),
                "Email" => $this->getEmail(),
                "Street" => $this->getStreet(),
                "Number" => $this->getNumber(),
                "PostalCode" => $this->getpostalCode(),
                "Bus" => $this->getBus(),
                "City" => $this->getCity(),
                "Telephone" => $this->getTelephone(),
                "LinkedinUrl" => $this->getLinkedinUrl()
            )
        ));
    }

    /**
     * Set bus
     *
     * @param string $bus
     *
     * @return Person
     */
    public function setBus($bus)
    {
        $this->bus = $bus;

        return $this;
    }

    /**
     * Get bus
     *
     * @return string
     */
    public function getBus()
    {
        return $this->bus;
    }

    /**
     * Set linkedinUrl
     *
     * @param string $linkedinUrl
     *
     * @return Person
     */
    public function setLinkedinUrl($linkedinUrl)
    {
        $this->linkedinUrl = $linkedinUrl;

        return $this;
    }

    /**
     * Get linkedinUrl
     *
     * @return string
     */
    public function getLinkedinUrl()
    {
        return $this->linkedinUrl;
    }

    /**
     * Get Organisation
     *
     * @return Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Set Organisation
     *
     * @param \AppBundle\Entity\Organisation $organisation
     */
    public function setOrganisation(\AppBundle\Entity\Organisation $organisation)
    {
        $this->organisation = $organisation;

        return $this;
    }
}
