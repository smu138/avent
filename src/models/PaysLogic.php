<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.04.18
 * Time: 11:29
 */

namespace App\models;


use App\Entity\Pays;
use \Doctrine\Common\Persistence\ObjectManager;

class PaysLogic extends Pays
{

    private $entityManager;
    private $payDate;
    private $isFirstRun = true;
    private $unique_num;

    public function makeCalculations(Pays $paysPicked, ObjectManager $entityManager){

        $this->entityManager = $entityManager;
        $this->unique_num = $paysPicked->getUniqueNum();


	    $query = $this->entityManager->createQuery(
		    'DELETE
	        FROM App\Entity\Pays p
	        WHERE p.unique_num = :unique_num'
	    )->setParameter('unique_num', $this->unique_num);

		$query->execute();

        $this->repeatIt($paysPicked);

    }



    private function repeatIt(Pays $data){


        $newPay = new Pays();

        $newPay->setUniqueNum($this->unique_num);

        $overallSumm = $data->getOverallSumm();

        $yearPercents = $data->getPercents();
        $newPay->setPercents($yearPercents);

        $overallMonths = $data->getTimeInMonths();
        $newPay->setTimeInMonths($overallMonths);

        $payNumber = $data->getPayNumber();
        if(empty($payNumber)) $data->setPayNumber(1);

        $mPs = $yearPercents / 100 / 12;

        //echo '$mPs = '.$mPs.'<br>';

        //echo '$needPay = '.$needPay.'<br>';

        //остаток от всего кредита
        if($this->isFirstRun){
            //это первый месяц
	        $pow = pow((1 + $mPs), $overallMonths);
	        $this->needPay = $overallSumm * ($mPs + ( $mPs / ($pow -1) ) );

	        $newPay->setPaySumm($this->needPay);


	        $pay_left = $overallSumm;
            $newPay->setPayLeft($overallSumm);
        }else{
            $pay_left = $overallSumm - $this->needPay;

	        $newPay->setPaySumm($this->needPay);
            $newPay->setPayLeft($pay_left);
        }

        //сумма прцентов
        $percents_to_pay = $pay_left * 0.1 / 12;
        $newPay->setPercentsToPay($percents_to_pay);
        //echo '$percents_to_pay = '.$percents_to_pay.'<br>';

        //основной долг
        $main_debt = $this->needPay - $percents_to_pay;
        $newPay->setMainDebt($main_debt);
        //echo '$main_debt = '.$main_debt.'<br>';

        $newPay->setPayNumber($payNumber + 1);

        $newPay->setOverallSumm($pay_left);

        //echo '$pay_left = '.$pay_left.'<br>';
        //echo '<hr>';

        $date = $data->getPayDate()->format('Y-m-d');
	    $start = new \DateTime($date, new \DateTimeZone("UTC"));
	    $month_later = clone $start;
	    $month_later->add(new \DateInterval("P1M"));

        $newPay->setPayDate($month_later);

        $this->entityManager->persist($newPay);
        $this->entityManager->flush();

        $this->isFirstRun = false;
        //и пройти дальше по циклу
        if($payNumber < $overallMonths){
            $this->repeatIt($newPay);
        }


    }
}