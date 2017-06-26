<html>
<head><title>CS143 Project 1B</title></head>
<body>
	<h2 style="color:orange;">CS 143 Movie Database</h2>
	<h3>Add Actor or Director: </h3>
	<form action="add_actor_or_director.php" method="GET">
  		<input type="radio" name="type" value="Actor"> Actor &nbsp; &nbsp;
 		<input type="radio" name="type" value="Director"> Director<br>
		<p><b>First Name</b></p> 
		<textarea name="first" cols="40" rows="1"></textarea><br/>
		<p><b>Last Name</b></p>
		<textarea name="last" cols="40" rows="1"></textarea><br><br/>
		<input type="radio" name="gender" value="Male"> Male &nbsp; &nbsp;
 		<input type="radio" name="gender" value="Female"> Female <br>
 		<p><b>Date of Birth</b></p>
 		<textarea name="birth" cols="40" rows="1"></textarea><br/>
 		<i>i.e. 19580708 (YYYYMMDD)</i><br>
 		<p><b>Date of Death</b></p>
 		<textarea name="death" cols="40" rows="1"></textarea><br/>
 		(leave blank if still alive)<br><br/>
 		<input type="submit" name="pressed" value="Add!!!">
	</form>
	<?php
		if(isset($_GET['pressed'])) {
		$type = $_GET["type"];
		$first = $_GET["first"];
		$last = $_GET["last"];
		$gender = $_GET["gender"];
		$birth = $_GET["birth"];
		$death = $_GET["death"];
		$dbc = new mysqli("localhost", "cs143", "", "CS143");
		
		$first = $dbc->real_escape_string($first);
		$last = $dbc->real_escape_string($last);
		
		
		if(!$dbc)
			echo "Connection failed!<br/>";
		$dbc->query("UPDATE MaxPersonID SET id = id + 1;");
		$res = $dbc->query("SELECT id FROM MaxPersonID;");
		$row = mysqli_fetch_row($res);
		$new_id = $row[0]; 
		if($type == "Actor"){
			if($death != "")
				$query = "INSERT INTO Actor VALUES($new_id, '$last', '$first', '$gender', '$birth', '$death');";
			else
				$query = "INSERT INTO Actor VALUES($new_id, '$last', '$first', '$gender', '$birth', NULL);";	
		}
		else{
			if($death != "")
				$query = "INSERT INTO Director VALUES($new_id, '$last', '$first', '$birth', '$death');";
			else
				$query = "INSERT INTO Director VALUES($new_id, '$last', '$first', '$birth', NULL);";
		}
		$dbc->query($query);
		mysqli_close($dbc);
		}
		echo "<a href=\"main.php\">Back to Main</a><br/>";
	?>
</body>
</html>