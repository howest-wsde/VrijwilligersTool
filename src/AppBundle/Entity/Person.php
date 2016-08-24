<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser;
use libphonenumber\PhoneNumberUtil as phoneUtil;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Form\UserRepository")
 *
 * @UniqueEntity(fields = "username", message = "person.username.already_used")
 * @UniqueEntity(fields = "email", message = "person.email.already_used")
 * @UniqueEntity(fields = "telephone", message = "person.telephone.already_used")
 *
 * @Vich\Uploadable
 *
 * @Assert\Callback({"AppBundle\Entity\Person", "validateContacts"}, groups = {"firstStep"})
 */
class Person extends OAuthUser implements UserInterface, \Serializable
{
    const NOMAIL = 0;
    const IMMEDIATELY = 1;
    const DAILY = 2;
    const WEEKLY = 3;
    const MONTHLY = 4;
    //if more constants are added then pls do adjust the digestcommand check for periodicity.

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank(message = "person.not_blank", groups = {"firstStep"})
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "person.min_message_one",
     *      maxMessage = "person.max_message",
     *      groups = {"firstStep"}
     * )
    */
    protected $firstname;

    /**
     * @var string
     * @Assert\NotBlank(message = "person.not_blank", groups = {"firstStep"})
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "person.min_message_one",
     *      maxMessage = "person.max_message",
     *      groups = {"firstStep"}
     * )
    */
    protected $lastname;

    /**
     * @var string
     * @Assert\Length(
     *      min = 2,
     *      max = 150,
     *      minMessage = "person.min_message",
     *      maxMessage = "person.max_message",
     *      groups = {"secondStep"}
     * )
     * @Assert\Regex(
     *     pattern = "/^[^ \/]+$/",
     *     message = "geen spaties of slashes",
     *     groups = {"secondStep"}
     * )
    */
    protected $username;

    /**
     * @var string
     */
    protected $passphrase;

    /**
     * @var string
     * @Assert\Email(
     *     message = "person.email.valid",
     *     checkHost = true,
     *     groups = {"firstStep"}
     * )
     */
    protected $email;

    /**
     * @Assert\NotBlank(groups = {"firstStep"})
     * @Assert\Length(
     *      min = 8,
     *      max = 4096,
     *      minMessage = "person.min_message",
     *      maxMessage = "person.max_message",
     *      groups = {"firstStep"}
     * )
     */
    protected $plainPassword;

    /**
     * @var string
     * @Assert\NotBlank(message = "person.not_blank", groups = {"secondStep"})
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "organisation.max_message",
     *      groups = {"secondStep"}
     * )
     */
    protected $street;

    /**
     * @var int
     * @Assert\Regex(
     *     pattern = "/^[0-9]*$/",
     *     message="person.not_numeric",
     *     groups = {"secondStep"}
     * )
     * @Assert\Range(
     *      min = 0,
     *      max = 999999,
     *      minMessage = "person.not_positive",
     *      groups = {"secondStep"}
     * )
     */
    protected $number;

    /**
     * @var int
     * @Assert\Length(
     * 		min = 1,
     *      max = 6,
     *      minMessage = "person.min_message_one",
     *      maxMessage = "person.max_message",
     *      groups = {"secondStep"}
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9]{1,6}$/",
     *     message="person.bus.valid",
     *     groups = {"secondStep"}
     * )
     */
    protected $bus;

    /**
     * @var int
     * @Assert\Regex(
     *     pattern = "/^[0-9]*$/",
     *     message="person.not_numeric",
     *     groups = {"secondStep"}
     * )
     * @Assert\Range(
     *      min = 1000,
     *      max = 9999,
     *      minMessage = "person.not_positive",
     *      maxMessage = "not_more_than",
     *      groups = {"secondStep"}
     * )
     * @Assert\Length(
     *      min = 4,
     *      max = 4,
     *      exactMessage = "person.exact",
     *      groups = {"secondStep"}
     * )
     */
    protected $postalcode;

    /**
     * @var string
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "person.min_message",
     *      maxMessage = "person.max_message",
     *      groups = {"secondStep"}
     * )
     */
    protected $city;

    /**
     * @var string
     */
    protected $latitude;

    /**
     * @var string
     */
    protected $longitude;

    /**
     * @var string
     * assert callback statement for telephone at top of class
     */
    protected $telephone;

    /**
     * @var string
     */
    protected $language;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="person_avatar", fileNameProperty="avatarName")
     *
     * @var File
     */
    protected $avatarFile;

    /**
     *
     * @var string
     */
    protected $avatarName;

    /**
     * Callback that check if either the email or telephone fields are valid
     * @param Organisation $org
     * @param ExecutionContextInterface $context
     * @return bool
     */
    public static function validateContacts($org, ExecutionContextInterface  $context)
    {
        $fields = 0;
        if ($org->getTelephone())
        {
            $org->validatePhoneNumber($org, $context);
        }
        else if ($org->getEmail() || $org->getContactOrganisation())
        {
            return true; // other validators are enabled with annotations
        }
        else {
            $context->buildViolation("person.one_of_three")
                ->atPath("telephone")
                ->addViolation();
            $context->buildViolation("person.one_of_three")
                ->atPath("email")
                ->addViolation();
            $context->buildViolation("person.one_of_three")
                ->atPath("contactOrganisation")
                ->addViolation();
        }
    }

    /**
     * Function to validate a phonenumber using the mid-service phone number bundle.
     * @param  ExecutionContextInterface    $context the context
     * @param  Organisation                 $org     an organisation
     */
    public function validatePhoneNumber($org, $context){
        $tel = $org->getTelephone();
        $phoneUtil = phoneUtil::getInstance();
        $pattern = '/^[0-9+\-\/\\\.\(\)\s]{6,35}$/i';
        $matchesPattern = preg_match($pattern, $tel);

        if($matchesPattern != 1){
            $context->buildViolation("person.telephone.numericWithExtra")
                ->atPath("telephone")
                ->addViolation();
        } else{
            $number = $phoneUtil->parse($tel, 'BE');
            if(!$phoneUtil->isValidNumber($number))
            {
                $context->buildViolation("person.telephone.valid")
                    ->atPath("telephone")
                    ->addViolation();
            }
            else
            {
                $org->setTelephone($phoneUtil->format($number,
                                \libphonenumber\PhoneNumberFormat::NATIONAL));
            }
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
    protected $linkedinUrl;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $skills;

    protected $isActive;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $testimonials;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $candidacies;

    /**
     * @var \AppBundle\Entity\Organisation
     */
    protected $organisation;

    /**
     * @var \AppBundle\Entity\Organisation
     */
    protected $contactOrganisation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $organisations;

    /**
     * @var int
     */
    private $digest = Person::IMMEDIATELY;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $liked_organisations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $liked_vacancies;

    /**
     * Whether or not a person desires weelchair-accessibility
     * @var bool
     */
    private $access = false;

    /**
     * Whether or not a person is willing to do non-renumerated volunteerwork
     * @var bool
     */
    private $renumerate = true;

    /**
     * Whether or not a person is willing to enter a longterm engagement
     * @var bool
     */
    private $longterm = true;

    /**
     * @var int
     */
    private $estimatedWorkInHours = 0;

    /**
     * @var string
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "vacancy.max_message"
     * )
     */
    private $socialInteraction = "normal";

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skill = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organisations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isActive = true;
        $this->setLanguage("nl");
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
        $this->telephone = $telephone;

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
     * Set language
     *
     * @param string $language
     *
     * @return Person
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
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
     * Set lat
     *
     * @param string $lat
     *
     * @return Person
     */
    public function setLatitude($lat)
    {
        $this->latitude = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set long
     *
     * @param string $long
     *
     * @return Person
     */
    public function setLongitude($long)
    {
        $this->longitude = $long;

        return $this;
    }

    /**
     * Get long
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Person
     */
    public function setAvatarFile(File $image = null)
    {
        $this->avatarFile = $image;
        if ($image) {
            $this->setAvatarName($this->getAvatarName());
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @param string $avatarName
     *
     * @return Person
     */
    public function setAvatarName($avatarName)
    {
        $this->avatarName = $avatarName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarName()
    {
        return $this->avatarName;
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
                "Language" => $this->getLanguage(),
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


    /**
     * Get contactOrganisation
     *
     * @return Organisation
     */
    public function getContactOrganisation()
    {
        return $this->contactOrganisation;
    }

    /**
     * Set contactOrganisation
     *
     * @param \AppBundle\Entity\Organisation $contactOrganisation
     *
     * @return Person
     */
    public function setContactOrganisation(\AppBundle\Entity\Organisation $contactOrganisation)
    {
        $this->contactOrganisation = $contactOrganisation;

        return $this;
    }

    /**
     * Add organisation
     *
     * @param \AppBundle\Entity\Organisation $organisation
     *
     * @return Person
     */
    public function addOrganisation(\AppBundle\Entity\Organisation $organisation)
    {
        $this->organisations[] = $organisation;

        return $this;
    }

    /**
     * Remove organisation
     *
      * @param \AppBundle\Entity\Organisation $organisation
     *
     * @return Person
     */
    public function removeOrganisation(\AppBundle\Entity\Organisation $organisation)
    {
        $this->organisations->removeElement($organisation);

        return $this;
    }

    /**
     * Get organisations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganisations()
    {
        return $this->organisations;
    }

    /**
     * Set digest
     *
     * @param int $digest
     *
     * @return Person
     */
    public function setDigest($digest)
    {
        $this->digest = $digest;

        return $this;
    }

    /**
     * Get digest
     *
     * @return int
     */
    public function getDigest()
    {
        return $this->digest;
    }

    /**
     * Add likedOrganisation
     *
     * @param \AppBundle\Entity\Organisation $likedOrganisation
     *
     * @return Person
     */
    public function addLikedOrganisation(\AppBundle\Entity\Organisation $likedOrganisation)
    {
        $this->liked_organisations[] = $likedOrganisation;

        return $this;
    }

    /**
     * Remove likedOrganisation
     *
     * @param \AppBundle\Entity\Organisation $likedOrganisation
     */
    public function removeLikedOrganisation(\AppBundle\Entity\Organisation $likedOrganisation)
    {
        $this->liked_organisations->removeElement($likedOrganisation);
    }

    /**
     * Get likedOrganisations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikedOrganisations()
    {
        return $this->liked_organisations;
    }

    /**
     * Add likedVacancy
     *
     * @param \AppBundle\Entity\Vacancy $likedVacancy
     *
     * @return Person
     */
    public function addLikedVacancy(\AppBundle\Entity\Vacancy $likedVacancy)
    {
        $this->liked_vacancies[] = $likedVacancy;

        return $this;
    }

    /**
     * Remove likedVacancy
     *
     * @param \AppBundle\Entity\Vacancy $likedVacancy
     */
    public function removeLikedVacancy(\AppBundle\Entity\Vacancy $likedVacancy)
    {
        $this->liked_vacancies->removeElement($likedVacancy);
    }

    /**
     * Get likedVacancies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikedVacancies()
    {
        return $this->liked_vacancies;
    }

    /**
     * Set accessible
     *
     * @param bool $accessible
     *
     * @return Person
     */
    public function setAccess($accessible)
    {
        $this->access = $accessible;

        return $this;
    }

    /**
     * Get accessible
     *
     * @return bool
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set renumerate
     *
     * @param bool $renumerate
     *
     * @return Person
     */
    public function setRenumerate($renumerate)
    {
        $this->renumerate = $renumerate;

        return $this;
    }

    /**
     * Get renumerate
     *
     * @return bool
     */
    public function getRenumerate()
    {
        return $this->renumerate;
    }

    /**
     * Set longterm
     *
     * @param bool $longterm
     *
     * @return Person
     */
    public function setLongterm($longterm)
    {
        $this->longterm = $longterm;

        return $this;
    }

    /**
     * Get longterm
     *
     * @return bool
     */
    public function getLongterm()
    {
        return $this->longterm;
    }

    /**
     * Set estimatedWorkInHours
     *
     * @param int $estimatedWorkInHours
     *
     * @return Person
     */
    public function setEstimatedWorkInHours($estimatedWorkInHours)
    {
        $this->estimatedWorkInHours = $estimatedWorkInHours;

        return $this;
    }

    /**
     * Get estimatedWorkInHours
     *
     * @return int
     */
    public function getEstimatedWorkInHours()
    {
        return $this->estimatedWorkInHours;
    }

    /**
     * Set socialInteraction
     *
     * @param string $socialInteraction
     *
     * @return Person
     */
    public function setSocialInteraction($socialInteraction)
    {
        $this->socialInteraction = $socialInteraction;

        return $this;
    }

    /**
     * Get socialInteraction
     *
     * @return string
     */
    public function getSocialInteraction()
    {
        return $this->socialInteraction;
    }

   /**
     * Get the class name
     *
     * @return string
     */
    public function getClassName()
    {
        $reflect = new \ReflectionClass($this);
        return $reflect->getShortName();
    }

    /**
     * returns if the class type is that of the given value
     *
     * @return bool
     */
    public function isOfType($type)
    {
        return $this->getClassName() == $type;
    }

    /**
     * helper function to enable the entity property in nested objects within ES documents.  The helper property simply contains the name of the object type (in other words: the class name)
     * @return String the classname of this entity
     */
    public function esGetEntityName()
    {
        return 'person';
    }

    /**
     * Getter for a full address in string form, like so:
     * 'Koning Alberstraat 12, 9900 Eeklo'
     */
    public function getAddress()
    {
        return $this->getStreet() . ' '
               . $this->getNumber() . ', '
               . $this->getCity() . ' '
               . $this->getPostalCode();
    }

    /**
     * Return latitude and longitude in the correct format for ES
     * @return string string formatted as lat, long
     */
    public function esGetLocation()
    {
        $lat = $this->getLatitude();
        $long = $this->getLongitude();

        if($lat && $long){
            return $this->getLatitude() . ', ' . $this->getLongitude();
        }

        return null;
    }

    /**
     * Get the id's of all liked organisations as an array
     * @return array
     */
    public function getLikedOrganisationIds(){
        $ids = [];
        $likedOrganisations = $this->getLikedOrganisations();
        if(!is_null($likedOrganisations) && !$likedOrganisations->isEmpty()){
            foreach ($likedOrganisations->toArray() as $key => $org) {
                $ids[] = $org->getId();
            }
        }

        return $ids;
    }
}
