<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Testimonial
 *
 * @ORM\Table(name="Testimonial", uniqueConstraints={@ORM\UniqueConstraint(name="Id", columns={"Id"})}, indexes={@ORM\Index(name="FKTestimonia20655", columns={"SenderId"}), @ORM\Index(name="FKTestimonia893114", columns={"ReceiverId"})})
 * @ORM\Entity
 */
class Testimonial
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Value", type="string", length=2000, nullable=false)
     */
    private $value;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="SenderId", referencedColumnName="Id")
     * })
     */
    private $senderid;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ReceiverId", referencedColumnName="Id")
     * })
     */
    private $receiverid;



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
     * Set value
     *
     * @param string $value
     *
     * @return Testimonial
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set senderid
     *
     * @param \AppBundle\Entity\User $senderid
     *
     * @return Testimonial
     */
    public function setSenderid(\AppBundle\Entity\User $senderid = null)
    {
        $this->senderid = $senderid;

        return $this;
    }

    /**
     * Get senderid
     *
     * @return \AppBundle\Entity\User
     */
    public function getSenderid()
    {
        return $this->senderid;
    }

    /**
     * Set receiverid
     *
     * @param \AppBundle\Entity\User $receiverid
     *
     * @return Testimonial
     */
    public function setReceiverid(\AppBundle\Entity\User $receiverid = null)
    {
        $this->receiverid = $receiverid;

        return $this;
    }

    /**
     * Get receiverid
     *
     * @return \AppBundle\Entity\User
     */
    public function getReceiverid()
    {
        return $this->receiverid;
    }
}
