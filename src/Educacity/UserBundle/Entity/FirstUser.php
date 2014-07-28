<?php

namespace Educacity\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("email")
 * @UniqueEntity("email")
 */
class FirstUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected  $id;

    /**
     * @ORM\Column(type="string", length=250, unique=true)
     * @Assert\Email();
     */
    protected $email;

    /**
     * @var date $registeredDate
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="registeredDate", type="datetime", nullable=true)
     * @Assert\Date()
     */
    protected $registeredDate;

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Educacity\UserBundle\Entity\date $registeredDate
     */
    public function setRegisteredDate($registeredDate)
    {
        $this->registeredDate = $registeredDate;
    }

    /**
     * @return \Educacity\UserBundle\Entity\date
     */
    public function getRegisteredDate()
    {
        return $this->registeredDate;
    }
}