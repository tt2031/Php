<?php
class PDF                                                         
{
public static function template_read($fileName,$ReplaceArray)
{
	if(!is_array($ReplaceArray))
	{
		echo "Problem with data,please check array parameter.";       //Проверка передаваемого массива на возможность восприятия данных как массив
		return;
	}
	
	
	if (!file_exists($fileName)) 
	{
		echo "This file isn't exist,sorry.";                         //Проверка существоания файла по переданному имени
		return;
	}
	
	
	
	define('FPDF_FONTPATH','/font/');   //подключаем папку с шрифтами и саму библиотеку
	require( 'fpdf.php' );

	$pdf = new FPDF();
	$pdf->SetFont('Arial','',12);        //Создаем новый pdf ,ставим шрифт ,добавляем страницу
	$pdf->AddPage();
	
	$file = fopen($fileName, "r");
	$flag_to_push="???";              //строка,на месте которой будут наши данные
	$size=strlen($flag_to_push);      
	$curr_elem=0;                    //итератор в массиве ,указывающий на текущее вставляемое значние
	$position=1;                     //используется для позиционирования вывода в pdf
	
	
	
	//$push_count=0;                //количество вставок,используется для проверки(оставляю в комментариях)
								   //для того,чтобы проверка соответствия количества вставок и пропусков работала
								   //необходимо расскоментировать строки,помеченые <--CHECK-->
	
	
	while(!feof($file))  			// работаем с файлом пока не дочитает его до конца
	{
		$tmp=fgets($file);			//1.читаем строку 
		
		if($endPoint=strripos($tmp,$flag_to_push))     //2.Проверяем,содержится ли в ней наш "пропуск"
		{
			
			$strrep=" ".$ReplaceArray[$curr_elem++];   //3.Формируем результирующую строку для вставки из соотв. элемента массива
			
			
			$pdf->Cell(40,10,substr_replace($tmp,$strrep,$endPoint,strlen($strrep)),12);
			
			
			//$push_count++;               //<--CHECK-->
		}
		else $pdf->Cell(40,10,$tmp,12);
		$pdf->Ln();
		$position++;
	}
	
	//if($push_count!=Array_length) echo "System can't complete all replacements , size of array and number of phases are different<br>";    //<--CHECK-->
	//else echo "All replacements are completed successfully<br> ";																			 //<--CHECK-->
		
		

	$pdf->Output();	
}
}


$pdf = new PDF(); 
$array1=array("Sascha","18","spb");
$string1=pdf::template_read("input1.txt",$array1);





?>