<?php

namespace AppBundle\Entity;

/**
 * Testquestion
 */
class Testquestion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $nr;

    /**
     * @var string
     */
    private $question;

    /**
     * @var integer
     */
    private $weight;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $answers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get nr
     *
     * @return integer
     */
    public function getNr()
    {
        return $this->nr;
    }

    /**
     * Set nr
     *
     * @param integer $nr
     *
     * @return Testquestion
     */
    public function setNr($nr)
    {
        $this->nr = $nr;

        return $this;
    }

    /**
     * Set question
     *
     * @param string $question
     *
     * @return Testquestion
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }


    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return Testquestion
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Add answer
     *
     * @param \AppBundle\Entity\Testanswer $answer
     *
     * @return Testquestion
     */
    public function addAnswer(\AppBundle\Entity\Testanswer $answer)
    {
        $this->answers[] = $answer;

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \AppBundle\Entity\Testanswer $answer
     */
    public function removeAnswer(\AppBundle\Entity\Testanswer $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}
