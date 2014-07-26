<?php

namespace Educacity\UserBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("email")
 * @UniqueEntity("email")
 */
class User implements UserInterface, \Serializable, EquatableInterface
{
    const AUTH_SALT = "Hyk3T1K0FWjo";

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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank();
     * @Assert\Length(min=6)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $salt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updatedDate;

    /**
     * @var date $registeredDate
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="registeredDate", type="datetime", nullable=true)
     * @Assert\Date()
     */
    protected $registeredDate;

    /**
     * @ORM\Column(name="validated", type="boolean", nullable=true, options={"default" = 0})
     */
    protected $validated = false;

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
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
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

    /**
     * @param mixed $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param mixed $updatedDate
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * @return mixed
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    public function serialize()
    {
        return serialize(array($this->id, $this->password, $this->email));
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->password, $this->email) = unserialize($serialized);
    }

    public function __toString()
    {
        return $this->getEmail();
    }

    public function isEqualTo(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        return $this->getEmail() == $user->getEmail();
    }

    public function eraseCredentials()
    {
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function getRoles()
    {
        if ($this->validated)
            return array('ROLE_USER');
        else
            return array('ROLE_USER_NOT_VALIDATED');
    }

    /**
     * @param mixed $validated
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;
    }

    /**
     * @return mixed
     */
    public function getValidated()
    {
        return $this->validated;
    }
}