<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use libphonenumber\PhoneNumberUtil as phoneUtil;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\Criteria;

/**
 * Organisation
 * @Assert\Callback({"AppBundle\Entity\organisation", "validatePhoneNumber"}, groups = {"secondStep"})
 *
 * @Vich\Uploadable
 *
 */
class Contact extends EntityBase
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
     * assert callback statement for telephone at top of class
     */
    private $telephone;

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
     * Constructor
     */
    public function __construct()
    {
        $this->vacancies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->administrators = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
