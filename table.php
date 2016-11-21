<?php
	//Database login information
	$host = 'athena.ecs.csus.edu';
	$user = 'reconnect_user';
	$db = 'reconnect';
	$pass = 'reconnect_db';
	private $conn;
	
	//$_POST values
	$patientID = $patientFirstName = $patientLastName = $table = "";
	$patientErr = $patientNameErr = $tableErr = "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		//Ensuring the user has input a patient
		if (empty($_POST["PatientLastName"]) or empty($_POST["PatientUserNumber"]))
		{
			$patientErr = "Patient Last Name or User Number is required";
		} else if (!empty($_POST["PatientUserNumber"])){
			$patientID = test_input($_POST["PatientUserNumber"]);
		} else {
			$patientLastName = test_input($_POST["PatientLastName"]);
			$patientFirstName = test_input($_POST["PatientFirstName"]);
			
			//Checking for invalid characters and then getting the PatientID
			if (!preg_match("/^[a-zA-Z]*$/", $patient))
			{
				$patientNameErr = "Only letters allowed";
			}else{
				$patientID = Statement::getPatientID(connect(), $patientLastName, $patientFirstName);
			}
		}
		
		//Ensuring a table has been selected
		if (empty($_POST["table"]))
		{
			$tableErr = "Table type is required";
		} else {
			$table = test_input($_POST["table"]);
		}
		
		//If no errors, get the table
		if (!($tableErr=="" and $patientNameErr=="" $patientErr==""))
		{
			$displayTable = get_table();
		}
	}
	
	
	private function test_input($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	private function connect()
	{
		$conn = SQL_Connect($host, $user, $db, $pass);
		return $conn;
	}
	
	private function get_data()
	{
		$sql = 'Select * from'.$table.'where PatientUID='.$patientID;
		$result = SQL_Statement::select($conn, $table, $patient);
		return $result;
	}
	
	//Pre-Condition: $table is instantiated
	private function get_table()
	{
		$tblScript = "";
		if !($conn) $conn = connect();
		$result = get_data();
		switch ($table)
		{
			case "all":
				$tblScript=getOverview($result);
				break;
			case "diagnosis":
				$tblScript=getDiagnosis($result);
				break;
			case "medication":
				$tblScript=getMedication($result);
				break;			
			case "sleep":
				$tblScript=getSleep($result);
				break;			
			case "heart":
				$tblScript=getHeart($result);
				break;
			//Default is $table = "activity"
			default:
				$tblScript=getActivity($result);
		}
	}
	
	//Pre-Condition: $data is a mysqli return object
	private function getOverview($data)
	{
		$row=$data->fetch_array(MYSQLI_ASSOC);
		$tblScript="<tr><th>Firstname</th><th>Lastname</th><th>Middle</th><th>PatientID</th><th>Email</th>
			<th>Phone Number</th><th>Medication</th><th>Diagnosis</th><th>Doctor</th></tr>";
		$tblScript.="<tr><td>".$row["Fname"]."</td><td>".$row["Lname"]."</td><td>".$row["Mname"]."</td><td>".
			$row["PatientUID"]."</td><td>".$row["Email"]."</td><td>".$row["PhoneNumber"]."</td><td>".$row["Medicine"].
			"</td><td>".$row["Diagnosis"]."</td><td>".$row["Doctor"]."</td></tr>";
		return $tblScript;
	}
	
	//Pre-Condition: $data is a mysqli return object
	private function getDiagnosis($data)
	{
		$row=$data->fetch_array(MYSQLI_ASSOC);
		$tableScript="<tr><th>PatientID</th><th>Diagnosis</th><th>Severity</th></tr>";
		$tableScript.="<tr><td>".$row["PatientUID"]."</td><td>".$row["Diagnosis"]."</td><td>".$row["Severity"]."</td></tr>";
		return $tableScript;
	}
	
	//Pre-Condition: $data is a mysqli return object
	private function getMedication($data)
	{
		$row=$data->fetch_array(MYSQLI_ASSOC);
		$tableScript="<tr><th>PatientID</th><th>Medication</th><th>DoctorID</th><th>Dosage</th></tr>";
		$tableScript.="<tr><td>".$row["PatientUID"]."</td><td>".$row["Medication"]."</td><td>".$row["DoctorUID"]."</td><td>".$row["Dosage"]."</td></tr>";
		return $tableScript;
	}
	
	//Pre-Condition: $data is a mysqli return object
	private function getSleep($data)
	{
		$row=$data->fetch_array(MYSQLI_ASSOC);
		$tableScript="<tr><th></th></tr>";
	}
?>

<!DOCTYPE html>
<html>
<body>

<!--Form for the user (presumably the doctor) to select the patient and the data for that patient
//The form will use the $_POST method to send the information to the same page
//Using the htmlspecialchars function to prevent javascript injection
//Patient can be found by userID or Last Name-->
<p><span class = "error"> * required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	Patient First Name:<br>
	<input type="text" name="PatientFirstName" value="<?php echo $patientFirstName;?>" autofocus><br>
	AND<br>
	Last Name:<br>
	<input type="text" name="PatientLastName" value="<?php echo $patientLastName;?>">
	<span class="error"><?php echo $patientNameErr; ?></span>
	<br>
	Or...<br>
	Patient User Number:<br>
	<input type="text" name="PatientUserNumber" value="<?php echo $patientID;?>">
	<span class="error">*<?php echo $patientErr;?></span>
	<br>
	Select Values:<br>
	<select name="table">
		<option value="all" <?php if ($table == "all") echo selected?>>Patient Overview</option>
		<option value="diagnosis" <?php if ($table == "diagnosis") echo selected?>>Diagnosis</option>
		<option value="medication" <?php if ($table == "medication") echo selected?>>Medication</option>
		<option value="sleep" <?php if ($table == "sleep") echo selected?>>Sleep Data</option>
		<option value="heart" <?php if ($table == "heart") echo selected?>>Heart Rate Data</option>
		<option value="activity" <?php if ($table == "activity") echo selected?>>Activity Data</option>
	</select>
	<span class="error">*<?php echo $tableErr;?></span>
	<br><br>
	<input type="submit" value="Submit">
</form>




</body>
</html>