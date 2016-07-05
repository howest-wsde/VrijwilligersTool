<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use libphonenumber\PhoneNumberUtil as phoneUtil;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * Organisation
 * @Assert\Callback({"AppBundle\Entity\organisation", "validatePhoneNumber"}, groups = {"secondStep"})
 *
 * @Vich\Uploadable
 *
 */
class Organisation extends EntityBase
{
    /**
     * @var int
    */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message = "organisation.not_blank", groups = {"firstStep"})
     * @Assert\Length(
     *      min = 4,
     *      max = 150,
     *      minMessage = "organisation.min_message",
     *      maxMessage = "organisation.max_message",
     *      groups = {"firstStep"}
     * )
    */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank(message = "organisation.not_blank", groups = {"firstStep"})
     * @Assert\Length(
     *      max = 3,
     *      maxMessage = "organisation.max_message", groups = {"firstStep"}
     * )
     */
    private $type;

    /**
     * @var bool
     */
    private $intermediary = false;

    /**
     * @var bool
     */
    private $deleted = false;

    /**
     * @var string
     * @Assert\NotBlank(message = "organisation.not_blank", groups = {"firstStep"})
     * @Assert\Length(
     *      min = 5,
     *      max = 2000,
     *      minMessage = "vacancy.min_message",
     *      maxMessage = "vacancy.max_message",
     *      groups = {"firstStep"}
     * )
     * @Assert\NotEqualTo("nieuw"), groups = {"firstStep"})
    */
    private $description;

    /**
     * @var \AppBundle\Entity\Person
     */
    private $creator;


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $administrators;


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $likers;

    /**
     * @var string
     * @Assert\Email(
     *     message = "organisation.email.valid",
     *     checkHost = true,
     *     groups = {"firstStep"}
     * )
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(message = "organisation.not_blank", groups = {"secondStep"})
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "organisation.max_message",
     *      groups = {"secondStep"}
     * )
     */
    private $street;

    /**
     * @var int
     * @Assert\Regex(
     *     pattern = "/^[0-9]*$/",
     *     message = "organisation.not_numeric",
     *     groups = {"secondStep"}
     * )
     * @Assert\Range(
     *      min = 0,
     *      max = 999999,
     *      minMessage = "organisation.not_positive",
     *      groups = {"secondStep"}
     * )
     */
    private $number;

    /**
     * @var int
     * @Assert\Length(
     * 		min = 1,
     *      max = 6,
     *      minMessage = "organisation.min_message_one",
     *      maxMessage = "organisation.max_message",
     *      groups = {"secondStep"}
     * )
     * @Assert\Regex(
     *     pattern = "/^[a-zA-Z0-9]{1,6}$/",
     *     message = "organisation.bus.valid",
     *     groups = {"secondStep"}
     * )
     */
    private $bus;

    /**
     * @var int
     * @Assert\Regex(
     *     pattern = "/^[0-9]*$/",
     *     message = "organisation.not_numeric",
     *     groups = {"secondStep"}
     * )
     * @Assert\Range(
     *      min = 1000,
     *      max = 9999,
     *      minMessage = "organisation.not_positive",
     *      maxMessage = "not_more_than",
     *      groups = {"secondStep"}
     * )
     * @Assert\Length(
     *      min = 4,
     *      max = 4,
     *      exactMessage = "organisation.exact",
     *      groups = {"secondStep"}
     * )
     */
    private $postalCode;

    /**
     * @var string
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "organisation.min_message",
     *      maxMessage = "organisation.max_message",
     *      groups = {"secondStep"}
     * )
     */
    private $city;

    /**
     * @var string
     */
    private $urlid;



    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="organisation_logo", fileNameProperty="logoName")
     *
     * @var File
     */
    protected $logoFile;

    /**
     *
     * @var string
     */
    protected $logoName;


    /**
     * @var string
     */
    private $slogan;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $sectors;

    /**
     * Set urlId
     *
     * @param string $urlId
     *
     * @return Organisation
     */
    public function setUrlId($urlId)
    {
        $this->urlid = $urlId;

        return $this;
    }

    /**
     * Get urlId
     *
     * @return string
     */
    public function getUrlId()
    {
        return $this->urlid;
    }

    /**
     * @var string
     * assert callback statement for telephone at top of class
     */
    private $telephone;

    /**
     * @var string
     * @Assert\Url(
     *    message = "organisation.url.valid",
     *    protocols = {"http", "https"},
     *    checkDNS = true,
     *    dnsMessage = "organisation.url.valid"
     * )
     */
    private $website;

    /**
     * Function to validate a phonenumber using the mid-service phone number bundle.
     * @param  ExecutionContextInterface    $context the context
     * @param  Organisation                 $org     an organisation
     */
    public static function validatePhoneNumber($org, ExecutionContextInterface $context){
        $tel = $org->getTelephone();

        if(!$tel){
            return true;
        }

        $phoneUtil = phoneUtil::getInstance();
        $pattern = '/^[0-9+\-\/\\\.\(\)\s]{6,35}$/i';
        $matchesPattern = preg_match($pattern, $tel);

        if($matchesPattern != 1){
            $context->buildViolation("organisation.telephone.numericWithExtra")
                ->atPath("telephone")
                ->addViolation();
        } else{
            $number = $phoneUtil->parse($tel, 'BE');
            if(!$phoneUtil->isValidNumber($number))
            {
                $context->buildViolation("organisation.telephone.valid")
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $vacancies;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Organisation
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set intermediary
     *
     * @param bool $intermediary
     *
     * @return Organisation
     */
    public function setIntermediary($intermediary)
    {
        $this->intermediary = $intermediary;

        return $this;
    }

    /**
     * Get intermediary
     *
     * @return bool
     */
    public function getIntermediary()
    {
        return $this->intermediary;
    }

    /**
     * Set deleted
     *
     * @param bool $deleted
     *
     * @return Organisation
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Organisation
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Organisation
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return Organisation
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
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
     * @return Organisation
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set creator
     *
     * @param \AppBundle\Entity\Person $creator
     *
     * @return Organisation
     */
    public function setCreator(\AppBundle\Entity\Person $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \AppBundle\Entity\Person
     */
    public function getCreator()
    {
        return $this->creator;
    }


    /**
     * Add administrator
     *
     * @param \AppBundle\Entity\Person $administrator
     *
     * @return Organisation
     */
    public function addAdministrator(\AppBundle\Entity\Person $administrator)
    {
        $this->administrators[] = $administrator;

        return $this;
    }

    /**
     * Remove administrator
     *
      * @param \AppBundle\Entity\Person $administrator
     *
     * @return Organisation
     */
    public function removeAdministrator(\AppBundle\Entity\Person $administrator)
    {
        $this->administrators->removeElement($administrator);

        return $this;
    }

    /**
     * Get administrators
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdministrators()
    {
        return $this->administrators;
    }



    /**
     * Add liker
     *
     * @param \AppBundle\Entity\Person $liker
     *
     * @return Organisation
     */
    public function addLiker(\AppBundle\Entity\Person $liker)
    {
        $this->likers[] = $liker;

        return $this;
    }

    /**
     * Remove liker
     *
     * @param \AppBundle\Entity\Person $liker
     */
    public function removeLiker(\AppBundle\Entity\Person $liker)
    {
        $this->likers->removeElement($liker);
    }

    /**
     * Get likers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikers()
    {
        return $this->likers;
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
     * @return Organisation
     */
    public function setLogoFile(File $image = null)
    {
        $this->logoFile = $image;
        if ($image) {
            $this->setLogoName($this->getLogoName());
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * @param string $logoName
     *
     * @return Organisation
     */
    public function setLogoName($logoName)
    {
        $this->logoName = $logoName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogoName()
    {
        return $this->logoName;
    }



    /**
     * Set slogan
     *
     * @param string $slogan
     *
     * @return Organisation
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * Get slogan
     *
     * @return string
     */
    public function getSlogan()
    {
        return $this->slogan;
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
                "Name" => $this->getName(),
                "Description" => $this->getDescription(),
                "Email" => $this->getEmail(),
                "Street" => $this->getStreet(),
                "Number" => $this->getNumber(),
                "PostalCode" => $this->getpostalCode(),
                "Bus" => $this->getBus(),
                "City" => $this->getCity(),
                "Telephone" => $this->getTelephone()
            )
        ));
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Organisation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Organisation
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
     * Set website
     *
     * @param string $website
     *
     * @return Organisation
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Organisation
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
     * @param \int $number
     *
     * @return Organisation
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return \int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set postalCode
     *
     * @param \int $postalCode
     *
     * @return Organisation
     */
    public function setpostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return \int
     */
    public function getpostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set bus
     *
     * @param \int $bus
     *
     * @return Organisation
     */
    public function setBus($bus)
    {
        $this->bus = $bus;

        return $this;
    }

    /**
     * Get bus
     *
     * @return \int
     */
    public function getBus()
    {
        return $this->bus;
    }


    /**
     * Set city
     *
     * @param string $city
     *
     * @return Organisation
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
     * Constructor
     */
    public function __construct()
    {
        $this->vacancies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->administrators = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add vacancy
     *
     * @param \AppBundle\Entity\Vacancy $vacancy
     *
     * @return Organisation
     */
    public function addVacancy(\AppBundle\Entity\Vacancy $vacancy)
    {
        $this->vacancies[] = $vacancy;

        return $this;
    }

    /**
     * Remove vacancy
     *
     * @param \AppBundle\Entity\Vacancy $vacancy
     */
    public function removeVacancy(\AppBundle\Entity\Vacancy $vacancy)
    {
        $this->vacancies->removeElement($vacancy);
    }

    /**
     * Get vacancies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacancies()
    {
        return $this->vacancies;
    }

    public function normaliseUrlId($em)
    {
        if (!is_null($this->getUrlId()))
        {
            $encoder = new UrlEncoder($em);
            $this->setUrlId($encoder->encode($this, $this->getName()));
        }
    }

    /**
     * Add sectors
     *
     * @param \AppBundle\Entity\Skill $sector
     *
     * @return Organisation
     */
    public function addSector(\AppBundle\Entity\Skill $sector)
    {
        $this->sectors[] = $sector;

        return $this;
    }

    /**
     * Remove sector
     *
     * @param \AppBundle\Entity\Skill $sector
     *
     * @return Organisation
     */
    public function removeSector(\AppBundle\Entity\Skill $sector)
    {
        $this->sectors->removeElement($sector);

        return $this;
    }

    /**
     * Get sectors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSectors()
    {
        return $this->sectors;
    }
}
