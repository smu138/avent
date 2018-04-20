<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 20.04.2018
 * Time: 22:04
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TaskController extends Controller {


	/**
	 * @Route("/logic", name="getLogic")
	 */
	public function doSomeLogic(){

		/*
		 * Дается случайный текст в файле 
		 * (файл следует приложить к заданию, 
		 * содержание не имеет значения, кодировка - UTF-8). 
		 * 
		 * PHP скрипт должен прочитать текст 
		 * и заменить каждое слово в тексте, 
		 * позиция которого 
		 * делится без остатка на 3 - словом -ТРИ-, 
		 * каждое слово, позиция которого делится без остатка на 5 - словом -ПЯТЬ-, 
		 * а если позиция слова делится без остатка и на 3 и на 5 - заменить его словом -ПЯТНАДЦАТЬ-. 
		 * 
		 * После обработки текста - результат сохранить в новом файле.
		 */

		$file = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/somefile');

		$words = explode(' ', $file);

		foreach ($words as $k => $word){
			$wordNumber = $k+1;
			if( ($wordNumber % 3 == 0) && ($wordNumber % 5 == 0)){
				$words[$k] = '-ПЯТНАДЦАТЬ-';
			}else if($wordNumber % 3 == 0){
				$words[$k] = '-ТРИ-';
			}else if($wordNumber % 5 == 0){
				$words[$k] = '-ПЯТЬ-';
			}
		}

		$back = implode(' ', $words);

		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/somefile_refactored', $back);

		$desc = '
		<p>
		Первый файл: <a download href="/somefile">Первый файл с основным текстом</a>
		</p>
		<p>
		Второй файл с заменами: <a download href="/somefile_refactored">Второй файл с заменами</a>
		</p>
		';
		return new Response($desc);
	}
}