<?php
class PDF                                                         
{
public static function template_read($fileName,$ReplaceArray)
{
	if(!is_array($ReplaceArray))
	{
		echo "Problem with data,please check array parameter.";       //�������� ������������� ������� �� ����������� ���������� ������ ��� ������
		return;
	}
	
	
	if (!file_exists($fileName)) 
	{
		echo "This file isn't exist,sorry.";                         //�������� ������������ ����� �� ����������� �����
		return;
	}
	
	
	
	define('FPDF_FONTPATH','/font/');   //���������� ����� � �������� � ���� ����������
	require( 'fpdf.php' );

	$pdf = new FPDF();
	$pdf->SetFont('Arial','',12);        //������� ����� pdf ,������ ����� ,��������� ��������
	$pdf->AddPage();
	
	$file = fopen($fileName, "r");
	$flag_to_push="???";              //������,�� ����� ������� ����� ���� ������
	$size=strlen($flag_to_push);      
	$curr_elem=0;                    //�������� � ������� ,����������� �� ������� ����������� �������
	$position=1;                     //������������ ��� ���������������� ������ � pdf
	
	
	
	//$push_count=0;                //���������� �������,������������ ��� ��������(�������� � ������������)
								   //��� ����,����� �������� ������������ ���������� ������� � ��������� ��������
								   //���������� ����������������� ������,��������� <--CHECK-->
	
	
	while(!feof($file))  			// �������� � ������ ���� �� �������� ��� �� �����
	{
		$tmp=fgets($file);			//1.������ ������ 
		
		if($endPoint=strripos($tmp,$flag_to_push))     //2.���������,���������� �� � ��� ��� "�������"
		{
			
			$strrep=" ".$ReplaceArray[$curr_elem++];   //3.��������� �������������� ������ ��� ������� �� �����. �������� �������
			
			
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