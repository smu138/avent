<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaysRepository")
 */
class Pays
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $pay_number;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $pay_date;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $main_debt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percents;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $overall_summ;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pay_left;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $time_in_months;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $unique_num;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percents_to_pay;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pay_summ;


    public function getId()
    {
        return $this->id;
    }

    public function getPayNumber(): ?int
    {
        return $this->pay_number;
    }

    public function setPayNumber(int $pay_number): self
    {
        $this->pay_number = $pay_number;

        return $this;
    }

    public function getPayDate(): ?\DateTime
    {
    	return $this->pay_date;
    }

    public function setPayDate(?\DateTime $pay_date): self
    {
        $this->pay_date = $pay_date;

        return $this;
    }

    public function getMainDebt(): ?float
    {
        return $this->main_debt;
    }

    public function setMainDebt(?float $main_debt): self
    {
        $this->main_debt = $main_debt;

        return $this;
    }

    public function getPercents(): ?float
    {
        return $this->percents;
    }

    public function setPercents(?float $percents): self
    {
        $this->percents = $percents;

        return $this;
    }

    public function getOverallSumm(): ?float
    {
        return $this->overall_summ;
    }

    public function setOverallSumm(?float $overall_summ): self
    {
        $this->overall_summ = $overall_summ;

        return $this;
    }

    public function getPayLeft(): ?float
    {
        return $this->pay_left;
    }

    public function setPayLeft(?float $pay_left): self
    {
        $this->pay_left = $pay_left;

        return $this;
    }

    public function getTimeInMonths(): ?int
    {
        return $this->time_in_months;
    }

    public function setTimeInMonths(?int $time_in_months): self
    {
        $this->time_in_months = $time_in_months;

        return $this;
    }

    public function getUniqueNum(): ?string
    {
        return $this->unique_num;
    }

    public function setUniqueNum(?string $unique_num): self
    {
        $this->unique_num = $unique_num;

        return $this;
    }

    public function getPercentsToPay(): ?float
    {
        return $this->percents_to_pay;
    }

    public function setPercentsToPay(?float $percents_to_pay): self
    {
        $this->percents_to_pay = $percents_to_pay;

        return $this;
    }

    public function getPaySumm(): ?float
    {
        return $this->pay_summ;
    }

    public function setPaySumm(?float $pay_summ): self
    {
        $this->pay_summ = $pay_summ;

        return $this;
    }

}
