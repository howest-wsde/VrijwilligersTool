<?php

namespace AppBundle\Entity;

/**
 * Testresult
 */
class Testresult
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Person
     */
    private $person;

    /**
     * @var \AppBundle\Entity\Testanswer
     */
    private $answer;


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
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return Testresult
     */
    public function setPerson(\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set answer
     *
     * @param \AppBundle\Entity\Testanswer $answer
     *
     * @return Testresult
     */
    public function setAnswer(\AppBundle\Entity\Testanswer $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \AppBundle\Entity\Testanswer
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}
