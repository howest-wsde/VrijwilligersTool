<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Organisation
 * @Assert\Callback({"AppBundle\Entity\organisation", "validateTelephone"})
 */
class Organisation
{
    /**
     * @var int
    */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message = "organisation.not_blank")
     * @Assert\Length(
     *      min = 4,
     *      max = 150,
     *      minMessage = "organisation.min_message",
     *      maxMessage = "organisation.max_message"
     * )
    */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank(message = "organisation.not_blank")
     * @Assert\Length(
     *      min = 20,
     *      max = 2000,
     *      minMessage = "vacancy.min_message",
     *      maxMessage = "vacancy.max_message"
     * )
    */
    private $description;

    /**
     * @var \AppBundle\Entity\Person
     */
    private $creator;

    /**
     * @var string
     * @Assert\Email(
     *     message = "organisation.email.valid",
     *     checkHost = true
     * )
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(message = "organisation.not_blank")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "organisation.max_message"
     * )
     */
    private $street;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="organisation.not_numeric"
     * )
     * @Assert\Range(
     *      min = 0,
     *      max = 999999,
     *      minMessage = "organisation.not_positive"
     * )
     */
    private $number;

    /**
     * @var int
     * @Assert\Length(
     * 		min = 1,
     *      max = 6,
     *      minMessage = "organisation.min_message_one",
     *      maxMessage = "organisation.max_message"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9]{1,6}$/",
     *     message="organisation.bus.valid"
     * )
     */
    private $bus;

    /**
     * @var int
     * @Assert\Type(
     *     type="integer",
     *     message="organisation.not_numeric"
     * )
     * @Assert\Range(
     *      min = 0,
     *      max = 9999,
     *      minMessage = "organisation.not_positive",
     *      maxMessage = "not_more_than"
     * )
     * @Assert\Length(
     *      min = 4,
     *      max = 4,
     *      exactMessage = "organisation.exact"
     * )
     */
    private $postalCode;

    /**
     * @var string
     * @Assert\Length(
     *      min = 1,
     *      max = 100,
     *      minMessage = "organisation.min_message",
     *      maxMessage = "organisation.max_message"
     * )
     */
    private $city;

    /**
     * @var string
     * assert callback statement for telephone at top of class
     */
    private $telephone;

    public static function validateTelephone($org, ExecutionContextInterface  $context)
    {
        $telephone = str_replace(' ', '', $org->getTelephone());

        if (!is_numeric($telephone)
        or !strlen($telephone) == 10)
        {
            $context->buildViolation("organisation.telephone.valid")
                ->atPath("telephone")
                ->addViolation();
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
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        $reflect = new \ReflectionClass($this);
        return json_encode( array(
            "Entity" => $reflect->getShortName(),
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
     * Set address
     *
     * @param string $address
     *
     * @return Organisation
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
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
     * Get name for url
     *
     * @return string
     */
    public function getNameUrl()
    {
        return str_replace(" ", "-", $this->name);
    }
}
