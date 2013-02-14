<?php

namespace Poe\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Weapon extends Item
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

      /**
     * @ORM\Column(type="decimal", scale=1)
     */
    protected $dps;

    /**
     * @ORM\Column(type="integer")
     */
    protected $minPhysDmg;

    /**
     * @ORM\Column(type="integer")
     */
    protected $maxPhysDmg;

    /**
     * @ORM\Column(type="decimal", scale=1)
     */
    protected $avgPhysDmg;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=true)
     */
    protected $avgEleDmg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $minFireDmg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $maxFireDmg;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=true)
     */
    protected $avgFireDmg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $minColdDmg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $maxColdDmg;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=true)
     */
    protected $avgColdDmg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $minLightningDmg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $maxLightningDmg;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=true)
     */
    protected $avgLightningDmg;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $crit;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $aps;

    public function calcDps()
    {
        $combinedAvgDmg = $this->calcAvgPhysDmg() + $this->calcAvgFireDmg() + $this->calcAvgColdDmg() + $this->calcAvgLightningDmg();

        $crit = 1 + $this->crit / 100;

        $dps = $this->aps * $combinedAvgDmg * $crit;

        return $dps;
    }

    public function calcAvgPhysDmg()
    {
        return $this->calcAvgDmg($this->minPhysDmg, $this->maxPhysDmg);
    }

    public function calcAvgEleDmg()
    {
        return $this->calcAvgFireDmg() + $this->calcAvgColdDmg() + $this->calcAvgLightningDmg();
    }

    public function calcAvgFireDmg()
    {
        return $this->calcAvgDmg($this->minFireDmg, $this->maxFireDmg);
    }

    public function calcAvgColdDmg()
    {
        return $this->calcAvgDmg($this->minColdDmg, $this->maxColdDmg);
    }

    public function calcAvgLightningDmg()
    {
        return $this->calcAvgDmg($this->minLightningDmg, $this->maxLightningDmg);
    }

    protected function calcAvgDmg($min, $max)
    {
        return ($min + $max) / 2;
    }

    // getset

    public function getDps()
    {
        return $this->dps;
    }

    public function setDps($dps)
    {
        $this->dps = $dps;

        return $this;
    }

    public function getMinPhysDmg()
    {
        return $this->minPhysDmg;
    }

    public function setMinPhysDmg($minPhysDmg)
    {
        $this->minPhysDmg = $minPhysDmg;

        return $this;
    }

    public function getMaxPhysDmg()
    {
        return $this->maxPhysDmg;
    }

    public function setMaxPhysDmg($maxPhysDmg)
    {
        $this->maxPhysDmg = $maxPhysDmg;

        return $this;
    }

    public function getAvgPhysDmg()
    {
        return $this->avgPhysDmg;
    }

    public function setAvgPhysDmg($avgPhysDmg)
    {
        $this->avgPhysDmg = $avgPhysDmg;

        return $this;
    }

    public function getMinFireDmg()
    {
        return $this->minFireDmg;
    }

    public function setMinFireDmg($minFireDmg)
    {
        $this->minFireDmg = $minFireDmg;

        return $this;
    }

    public function getMaxFireDmg()
    {
        return $this->maxFireDmg;
    }

    public function setMaxFireDmg($maxFireDmg)
    {
        $this->maxFireDmg = $maxFireDmg;

        return $this;
    }

    public function getAvgFireDmg()
    {
        return $this->avgFireDmg;
    }

    public function setAvgFireDmg($avgFireDmg)
    {
        $this->avgFireDmg = $avgFireDmg;

        return $this;
    }

    public function getMinColdDmg()
    {
        return $this->minColdDmg;
    }

    public function setMinColdDmg($minColdDmg)
    {
        $this->minColdDmg = $minColdDmg;

        return $this;
    }

    public function getMaxColdDmg()
    {
        return $this->maxColdDmg;
    }

    public function setMaxColdDmg($maxColdDmg)
    {
        $this->maxColdDmg = $maxColdDmg;

        return $this;
    }

    public function getAvgColdDmg()
    {
        return $this->avgColdDmg;
    }

    public function setAvgColdDmg($avgColdDmg)
    {
        $this->avgColdDmg = $avgColdDmg;

        return $this;
    }

    public function getMinLightningDmg()
    {
        return $this->minLightningDmg;
    }

    public function setMinLightningDmg($minLightningDmg)
    {
        $this->minLightningDmg = $minLightningDmg;

        return $this;
    }

    public function getMaxLightningDmg()
    {
        return $this->maxLightningDmg;
    }

    public function setMaxLightningDmg($maxLightningDmg)
    {
        $this->maxLightningDmg = $maxLightningDmg;

        return $this;
    }

    public function getAvgLightningDmg()
    {
        return $this->avgLightningDmg;
    }

    public function setAvgLightningDmg($avgLightningDmg)
    {
        $this->avgLightningDmg = $avgLightningDmg;

        return $this;
    }

    public function getAvgEleDmg()
    {
        return $this->avgEleDmg;
    }

    public function setAvgEleDmg($avgEleDmg)
    {
        $this->avgEleDmg = $avgEleDmg;

        return $this;
    }

    public function getCrit()
    {
        return $this->crit;
    }

    public function setCrit($crit)
    {
        $this->crit = $crit;

        return $this;
    }

    public function getAps()
    {
        return $this->aps;
    }

    public function setAps($aps)
    {
        $this->aps = $aps;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}
