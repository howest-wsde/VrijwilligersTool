<?php

namespace AppBundle\Entity;

/**
 * Skill
 */
class Skill extends EntityBase
{
    /**
     * Constructor
     *
     * @param string name
     */
    public function __construct($name = "")
    {
        $this->name = $name;
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Skill
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $vacancies;

    /**
     * Get vacancies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVacancies()
    {
        return $this->vacancies;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Skill
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
     * @return Skill
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
                "Name" => $this->getName()
            )
        ));
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Skill $parent
     *
     * @return Skill
     */
    public function setParent(\AppBundle\Entity\Skill $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Skill
     */
    public function getParent()
    {
        return $this->parent;
    }
}
