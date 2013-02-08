<?php

namespace Poe\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Item
{
    const LEAGUE_HARDCORE = 0;

    const LEAGUE_DEFAULT = 1;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="ItemType", inversedBy="items")
     */
    protected $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quality;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $minPhysicalDamage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $maxPhysicalDamage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $minFireDamage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $maxFireDamage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $minColdDamage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $maxColdDamage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $minLightningDamage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $maxLightningDamage;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $criticalStrikeChance;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $attacksPerSecond;

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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $intReq;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $dexReq;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $strReq;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $lvlReq;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $verified;

    /**
     * @ORM\Column(type="string")
     */
    protected $icon;

    /**
     * @ORM\Column(type="integer")
     */
    protected $league;

    /**
     * @ORM\Column(type="string")
     */
    protected $sockets;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $identified;

    /**
     * @ORM\Column(type="string")
     */
    protected $accountName;

    /**
     * @ORM\Column(type="integer")
     */
    protected $threadId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $mapLvl;

    public function getMapLvl()
    {
        return $this->mapLvl;
    }

    public function setMapLvl($mapLvl)
    {
        $this->mapLvl = $mapLvl;

        return $this;
    }

    public function getMinFireDamage()
    {
        return $this->minFireDamage;
    }

    public function setMinFireDamage($minFireDamage)
    {
        $this->minFireDamage = $minFireDamage;

        return $this;
    }

    public function getMaxFireDamage()
    {
        return $this->maxFireDamage;
    }

    public function setMaxFireDamage($maxFireDamage)
    {
        $this->maxFireDamage = $maxFireDamage;

        return $this;
    }

    public function getMinColdDamage()
    {
        return $this->minColdDamage;
    }

    public function setMinColdDamage($minColdDamage)
    {
        $this->minColdDamage = $minColdDamage;

        return $this;
    }

    public function getMaxColdDamage()
    {
        return $this->maxColdDamage;
    }

    public function setMaxColdDamage($maxColdDamage)
    {
        $this->maxColdDamage = $maxColdDamage;

        return $this;
    }

    public function getMinLightningDamage()
    {
        return $this->minLightningDamage;
    }

    public function setMinLightningDamage($minLightningDamage)
    {
        $this->minLightningDamage = $minLightningDamage;

        return $this;
    }

    public function getMaxLightningDamage()
    {
        return $this->maxLightningDamage;
    }

    public function setMaxLightningDamage($maxLightningDamage)
    {
        $this->maxLightningDamage = $maxLightningDamage;

        return $this;
    }

    public function getAttacksPerSecond()
    {
        return $this->attacksPerSecond;
    }

    public function setAttacksPerSecond($attacksPerSecond)
    {
        $this->attacksPerSecond = $attacksPerSecond;

        return $this;
    }

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

    public function getCriticalStrikeChance()
    {
        return $this->criticalStrikeChance;
    }

    public function setCriticalStrikeChance($criticalStrikeChance)
    {
        $this->criticalStrikeChance = $criticalStrikeChance;

        return $this;
    }

    public function getThreadId()
    {
        return $this->threadId;
    }

    public function setThreadId($threadId)
    {
        $this->threadId = $threadId;

        return $this;
    }

    public function getQuality()
    {
        return $this->quality;
    }

    public function setQuality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    public function getAccountName()
    {
        return $this->accountName;
    }

    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;

        return $this;
    }

    public function getMinPhysicalDamage()
    {
        return $this->minPhysicalDamage;
    }

    public function setMinPhysicalDamage($minPhysicalDamage)
    {
        $this->minPhysicalDamage = $minPhysicalDamage;

        return $this;
    }

    public function getMaxPhysicalDamage()
    {
        return $this->maxPhysicalDamage;
    }

    public function setMaxPhysicalDamage($maxPhysicalDamage)
    {
        $this->maxPhysicalDamage = $maxPhysicalDamage;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getVerified()
    {
        return $this->verified;
    }

    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function getLeague()
    {
        return $this->league;
    }

    public function setLeague($league)
    {
        $this->league = $league;

        return $this;
    }

    public function getSockets()
    {
        return $this->sockets;
    }

    public function setSockets($sockets)
    {
        $this->sockets = $sockets;

        return $this;
    }

    public function getIdentified()
    {
        return $this->identified;
    }

    public function setIdentified($identified)
    {
        $this->identified = $identified;

        return $this;
    }

    public function getLvlReq()
    {
        return $this->lvlReq;
    }

    public function setLvlReq($lvlReq)
    {
        $this->lvlReq = $lvlReq;

        return $this;
    }

    public function getIntReq()
    {
        return $this->intReq;
    }

    public function setIntReq($intReq)
    {
        $this->intReq = $intReq;

        return $this;
    }

    public function getDexReq()
    {
        return $this->dexReq;
    }

    public function setDexReq($dexReq)
    {
        $this->dexReq = $dexReq;

        return $this;
    }

    public function getStrReq()
    {
        return $this->strReq;
    }

    public function setStrReq($strReq)
    {
        $this->strReq = $strReq;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}
