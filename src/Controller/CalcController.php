<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.04.18
 * Time: 14:44
 */

namespace App\Controller;


use App\Entity\Pays;
use App\models\PaysLogic;
use Doctrine\DBAL\Types\DateTimeType;
use App\Entity\PaysModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CalcController extends Controller
{


    /**
     * @Route("/calc", name="getCalculate")
     */
    public function calculate(Request $request, SessionInterface $session, PaysLogic $pl){


	    $requestF = Request::createFromGlobals();

    	$json = $requestF->getContent();

	    $formData = (array) json_decode($json, true);


        $unique_num = $session->get('unique_num', md5(date('YmdHis')));
		$session->set('unique_num', $unique_num);



	    $pay = new Pays();

        $form = $this->createFormBuilder($pay /* array('csrf_protection' => false)*/)
            ->add('overall_summ', IntegerType::class,[
	            'label' => 'Требуемая сумма',
            	'attr' => [
            		'ng-model' => 'pays.overall_summ'
	            ]
            ])
            ->add('time_in_months', IntegerType::class,[
	            'label' => 'На сколько месяцев кредит',
	            'attr' => [
		            'ng-model' => 'pays.time_in_months'
	            ]
            ])
            ->add('percents', IntegerType::class,[
	            'label' => 'Проценты (год)',
	            'attr' => [
		            'ng-model' => 'pays.percents'
	            ]
            ])
            ->add('pay_date', DateType::class,[
	            'widget' => 'single_text',
	            'format' => 'yyyy-MM-dd',
	            'label' => 'Дата платежа',
	            'attr' => [
		            'ng-model' => 'pays.pay_date',
	            ]
            ])
            ->add('save', SubmitType::class, array(
            	'label' => 'Рассчитать платеж',

	            ))
            ->getForm();

        //$form->handleRequest($request);

	    //die();
        //if ($form->isSubmitted() && $form->isValid()) {

		if(!empty($formData['form'])){
			//$pay = $form->getData();


			$date = new \DateTime($formData['form']['pay_date']);


			$pay->setOverallSumm(round((float)$formData['form']['overall_summ']));
			$pay->setPayDate($date);
			$pay->setPercents($formData['form']['percents']);
			$pay->setTimeInMonths($formData['form']['time_in_months']);

			$pay->setUniqueNum($unique_num);

			$entityManager = $this->getDoctrine()->getManager();
			$pl->makeCalculations($pay, $entityManager);



			return $this->getAllPays($unique_num);
		}


        //}

        return $this->render('base.html.twig', array('pays_form' => $form->createView()));
    }





    public function getAllPays($unique_num){

	    $repository = $this->getDoctrine()->getRepository(Pays::class);

	    $paysAll = $repository->findBy(array('unique_num' => $unique_num),array('pay_number' => 'ASC'));

	    $jsonObjects = [];

	    $serializer = $this->container->get('serializer');
	    foreach($paysAll as $onePay){

		    $dateformatted = $onePay->getPayDate()->format('Y-m-d');
		    $onePay->frt = $dateformatted;
		    $jsonObjects[] = $serializer->serialize($onePay, 'json');

	    }


	    $jsonResponse = new JsonResponse(['data' => $jsonObjects]);

	    return $jsonResponse;
    }




	/**
	 * @Route("/pays", name="getAllPays")
	 */
    public function getPays(Request $request, SessionInterface $session, PaysLogic $pl){


	    $unique_num = $session->get('unique_num');

	    if(empty($unique_num)){
	    	throw new BadRequestHttpException('Сначала надо создать новый расчет!');
	    }
	    $repository = $this->getDoctrine()->getRepository(Pays::class);

	    $paysAll = $repository->findBy(array('unique_num' => $unique_num),array('pay_number' => 'ASC'));

		$jsonObjects = [];

	    $serializer = $this->container->get('serializer');
	    foreach($paysAll as $onePay){

		    $dateformatted = $onePay->getPayDate()->format('Y-m-d');
		    $onePay->frt = $dateformatted;
		    $jsonObjects[] = $serializer->serialize($onePay, 'json');

	    }


	    $jsonResponse = new JsonResponse(['data' => $jsonObjects]);

	    return $jsonResponse;
    }
}