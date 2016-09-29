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
     * @var string
     */
    private $history;

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
     * Set history
     *
     * @param string $history
     *
     * @return Testanswer
     */
    public function setHistory($history)
    {
        $this->history = $history;

        return $this;
    }

    /**
     * Get history
     *
     * @return string
     */
    public function getHistory()
    {
        return $this->history;
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
    /**
     * @var integer
     */
    private $type1score;

    /**
     * @var integer
     */
    private $type2score;

    /**
     * @var integer
     */
    private $type3score;

    /**
     * @var integer
     */
    private $type4score;


    /**
     * Set type1score
     *
     * @param integer $type1score
     *
     * @return Testanswer
     */
    public function setType1score($type1score)
    {
        $this->type1score = $type1score;

        return $this;
    }

    /**
     * Get type1score
     *
     * @return integer
     */
    public function getType1score()
    {
        return $this->type1score;
    }

    /**
     * Set type2score
     *
     * @param integer $type2score
     *
     * @return Testanswer
     */
    public function setType2score($type2score)
    {
        $this->type2score = $type2score;

        return $this;
    }

    /**
     * Get type2score
     *
     * @return integer
     */
    public function getType2score()
    {
        return $this->type2score;
    }

    /**
     * Set type3score
     *
     * @param integer $type3score
     *
     * @return Testanswer
     */
    public function setType3score($type3score)
    {
        $this->type3score = $type3score;

        return $this;
    }

    /**
     * Get type3score
     *
     * @return integer
     */
    public function getType3score()
    {
        return $this->type3score;
    }

    /**
     * Set type4score
     *
     * @param integer $type4score
     *
     * @return Testanswer
     */
    public function setType4score($type4score)
    {
        $this->type4score = $type4score;

        return $this;
    }

    /**
     * Get type4score
     *
     * @return integer
     */
    public function getType4score()
    {
        return $this->type4score;
    }
    /**
     * @var integer
     */
    private $type5score;


    /**
     * Set type5score
     *
     * @param integer $type5score
     *
     * @return Testanswer
     */
    public function setType5score($type5score)
    {
        $this->type5score = $type5score;

        return $this;
    }

    /**
     * Get type5score
     *
     * @return integer
     */
    public function getType5score()
    {
        return $this->type5score;
    }
}
