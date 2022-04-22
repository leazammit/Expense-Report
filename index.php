<html>
<head>
    <title>PHP Expense Report Test</title>
</head>
<body>

<?php 
	//Object class of results
	class expenseItem {
		var $name;
		var $amount = 0;
		
		function set_name($input_name) {
			$this->name = $input_name;
		}
		function get_name() {
			return $this->name;
		}
		
		function set_amount($input_price,$input_am) {
			$this->amount = ($input_price*$input_am);
		}
		function get_amount() {
			return $this->amount;
		}
		function calculate($price,$quantity)  {
			$this->amount = $this->amount + ($price*$quantity);
			//return $amount;
		}
	}

	$fileName = "sampleIn.csv";
	
	//Expense Report Calculation
	function createExpenseReport($fileName)
	{
		$expense = array();
		$openfile = fopen($fileName, "r");
		
		//Read input file
		while (!feof($openfile) ) 
		{

			$currentItem = fgetcsv($openfile, 1024);
			$index = 0;
			
			//print $currentItem[0]  ."  ".  $currentItem[1]."  ". $currentItem[2] . "<BR>";
			
			if(in_array($currentItem[0],array_column($expense,'name'),true))
			{
				$index = array_search($currentItem[0],array_column($expense,'name'));
				//echo $expense[$index]->get_amount()."  ";
				//print $currentItem[0]  ."  ".  $currentItem[1]."  ". $currentItem[2]." ";
				$expense[$index]->calculate($currentItem[1],$currentItem[2]);
				//echo $expense[$index]->get_amount()."  ". "<BR>";
				
			}	
			else
			{
				$current = new expenseItem();
				$current->set_name($currentItem[0]);
				$current->set_amount($currentItem[1],$currentItem[2]);
				array_push($expense,$current);
			}	
			
			
			
		}

		fclose($openfile);
		return $expense;
	}
	
	//Writing result to CSV
	function writeToCSV($resultArray)
	{
		$openfile2 = fopen("ExpenseResult.csv", "w");
		
		foreach ($resultArray as $row) 
		{ 
			$line = $row->get_name() . "," . $row->get_amount() . "<BR>";
			fwrite($openfile2,$line);
		}
		
		fclose($openfile2);
	}
	
	$result = createExpenseReport($fileName);
	writeToCSV($result);
	//print_r( $result);
	
	//Display Results
	echo "<table border='1' style='border-collapse: 
    collapse;border-color: silver;'>";  
    echo "<tr style='font-weight: bold;'>";  
    echo "<td width='150' align='center'>Expense Report</td>";  
    echo "</tr>";
	foreach ($result as $row) 
     { 
      echo '<td width="150" align=center>' . $row->get_name() . '</td>';
      echo '<td width="150" align=center>' . $row->get_amount() . '</td>';
      echo '</tr>';
     }
	 
	
	
?>


<form  method="post">
    <input type="submit" name="Download" value="Download" >
    </form>
	
	<?php
		if(isset($_POST['Download'])){
			header("Content-Type: application/octet-stream");
			header("Content-Transfer-Encoding: Binary");
			header("Content-disposition: attachment; filename=\"ExpenseResult.csv\""); 
			echo readfile($result);
		}
		else ();
	?>
	
</body>
</html>
