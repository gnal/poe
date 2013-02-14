<?php

namespace Poe\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Armor extends Item
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $armour;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $evasionRating;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $energyShield;

    public function getArmour()
    {
        return $this->armour;
    }

    public function setArmour($armour)
    {
        $this->armour = $armour;

        return $this;
    }

    public function getEvasionRating()
    {
        return $this->evasionRating;
    }

    public function setEvasionRating($evasionRating)
    {
        $this->evasionRating = $evasionRating;

        return $this;
    }

    public function getEnergyShield()
    {
        return $this->energyShield;
    }

    public function setEnergyShield($energyShield)
    {
        $this->energyShield = $energyShield;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}
