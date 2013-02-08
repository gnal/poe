<?php

namespace Poe\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Item
{
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
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string")
     */
    protected $thread;

    public function getThread()
    {
        return $this->thread;
    }

    public function setThread($thread)
    {
        $this->thread = $thread;

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
