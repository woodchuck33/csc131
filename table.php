<?php
	//Database login information
	$host = 'athena.ecs.csus.edu';
	$user = 'reconnect_user';
	$db = 'reconnect';
	$pass = 'reconnect_db';
	
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
			$patientName = test_input($_POST["PatientLastName"]);
			
			//Checking for invalid characters
			if (!preg_match("/^[a-zA-Z]*$/", $patient))
			{
				$patientNameErr = "Only letters allowed";
			}
		}
		
		//Ensuring a table has been selected
		if (empty($_POST["table"]))
		{
			$tableErr = "Table type is required";
		} else {
			$table = test_input($_POST["table"]);
		}
	}
	
	
	function test_input($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	function connect()
	{
		$conn = SQL_Connect($host, $user, $db, $pass);
		return $conn;
	}
	
	function get_data($conn)
	{
		$sql = 'Select * from'.$table.'where PatientUid=';
		if ($patientID=="")
		{
			$sql .= 
		}
		$result = SQL_Statement.select($conn, $table, $patient);
		return $result;
	}
	
	function get_table()
	{
		$conn = connect();
		$result = get_data($conn);
		
	}
?>

<!DOCTYPE html>
<html>
<body>

//Form for the user (presumably the doctor) to select the patient and the data for that patient
//The form will use the $_POST method to send the information to the same page
//Using the htmlspecialchars function to prevent javascript injection
//Patient can be found by userID or Last Name
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