<html>
<head><title>CS143 Project 1B</title></head>
<body>
	<h2 style="color:orange;">CS 143 Movie Database</h2>
	<p>Actor: </p>
	<form action="add_actor_to_film.php" method="GET">
		<textarea name="a_search" cols="40" rows="1"><?php echo $_GET["a_search"];?></textarea><br/>
	<p>Movie: </p>
		<textarea name="m_search" cols="40" rows="1"><?php echo $_GET["m_search"];?></textarea><br/>
		<?php echo "<br>"; ?>
		<input type="submit" name="press" value="Find Both" />
		
		<?php	
			echo "<br><br>";
			$dbc = new mysqli("localhost", "cs143", "", "CS143"); //change to: "cs143", "", "CS143"
			
			if(isset($_GET['press'])) {
				$search = $_GET["a_search"];
				$name = $dbc->real_escape_string($search);
				$name = explode(" ", $search);			
				if(count($name) == 1)
					$query = "SELECT id, dob, first, last FROM Actor WHERE first regexp '$name[0]' OR last regexp '$name[0]' order by last, first, dob, id;";
				else
					$query = "SELECT id, dob, first, last FROM Actor WHERE concat(first, ' ', last) regexp '($name[0] | $name[1])' order by last, first, dob, id;";
		
				if($search == "")
					$query = "SELECT id, dob, first, last FROM Actor order by last, first, dob, id;";
				
				$res = $dbc->query($query);
				echo '<select name="actor_1">';
				while($row = mysqli_fetch_row($res)){
					echo '<option value='.$row[0].'>'.$row[2].' '.$row[3].' ('.$row[1].')'.'</option>';
				}
				echo '</select></br>';
			

				$title = $_GET["m_search"];
				$title = $dbc->real_escape_string($title);
				if($title == "")
					$query1 = "SELECT id, year, title FROM Movie order by title, year, id;";
				else
					$query1 = "SELECT id, year, title FROM Movie where title regexp '$title' order by title, year, id;";
				
				$res1 = $dbc->query($query1);
				echo '<select name="movie_1">';
				while($row1 = mysqli_fetch_row($res1)){
					echo '<option value='.$row1[0].'>'.$row1[2].' ('.$row1[1].')'.'</option>';
				}
				echo '</select></br>';
			}
		?>

		
		<p><b>Role</b></p> 
		<textarea name="role" cols="32" rows="1"></textarea><br/>
		<input type="submit" name="add" value="Add!!!">
	</form>
	
	
		
	
	
	

	<?php
		if(isset($_GET['add'])) {
			$role = $_GET["role"];
			$aid = $_GET["actor_1"];
			$mid = $_GET["movie_1"];
			$role = $dbc->real_escape_string($role);
			
			if($aid != "" && $mid != "")
			{
				$query_z = "INSERT INTO MovieActor VALUES($mid, $aid, '$role');";
				$dbc->query($query_z);
			}
				
	
	
		mysqli_close($dbc);
		}
		echo "<a href=\"main.php\">Back to Main</a><br/>";
	?>
</body>
</html>