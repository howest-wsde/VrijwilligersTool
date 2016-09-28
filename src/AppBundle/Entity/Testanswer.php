<?php

namespace AppBundle\Entity;

/**
 * Testanswer
 */
class Testanswer
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $question;

    /**
     * @var string
     */
    private $answer;

    /**
     * @var integer
     */
    private $weight;

    /**
     * @var integer
     */
    private $skill;


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
     * Set question
     *
     * @param integer $question
     *
     * @return Testanswer
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return integer
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param string $answer
     *
     * @return Testanswer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return Testanswer
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
     * Set skill
     *
     * @param integer $skill
     *
     * @return Testanswer
     */
    public function setSkill($skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill
     *
     * @return integer
     */
    public function getSkill()
    {
        return $this->skill;
    }
}
