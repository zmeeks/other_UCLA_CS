<html>
<head><title>CS143 Project 1B</title></head>
<body>
	<h2 style="color:orange;">CS 143 Movie Database</h2>
	<h1><font color="blue">S</font><font color="red">e</font><font color="gold">a</font><font color="blue">r</font><font color="green">c</font><font color="red">h</font><font color="orange"> Film Information</font></h1>
	<p>Search for actor/actress or movie info below: </p>
	<p>Example: <tt>Kevin Bacon</tt></p>
	<form action="search.php" method="GET">
		<textarea name="search" cols="50" rows="1"></textarea><br/>
		<input type="submit" name="button" value="Search" />
	</form>
	
	<?php
		$dbc = new mysqli("localhost", "cs143", "", "CS143"); //change to: "cs143", "", "CS143"
		if(!$dbc)
			echo "Connection Failed!<br>";
		$search = $_GET["search"];
		$search = $dbc->real_escape_string($search);
		$name = explode(" ", $search);
		
		if(count($name) == 1)
			$query = "SELECT id, dob as 'Date of Birth', first, last, dod FROM Actor WHERE first regexp '$name[0]' OR last regexp '$name[0]' order by last, first, dob, id;";
		else
			$query = "SELECT id, dob as 'Date of Birth', first, last, dod FROM Actor WHERE concat(first, ' ', last) regexp '$search' order by last, first, dob, id;";
		
		if(isset($_GET['button'])) {
			if($search != ""){
				$res = $dbc->query($query);
			}
			else{
				$query = "SELECT id, dob as 'Date of Birth', first, last, dod FROM Actor order by order by last, first, dob, id;";
				$res = $dbc->query($query);
			}
		
		
		

			echo '<h3>Matching Actors:</h3>';
		
			echo '<table border=1 cellspacing=1 cellpadding=2>';
			echo '<thead>';
				echo '<tr align=center>';
					
						echo '<td><b><font color="blue">',"Name",'</font></b></td>';
						echo '<td><b><font color="blue">',"Date of Birth",'</font></b></td>';
					
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
				
					while($row = mysqli_fetch_row($res)) {
						$aid = $row[0];
						$dob = $row[1];
						$first = $row[2];
						$last = $row[3];
						$dod = $row[4];
						echo '<tr align=center>';
						echo '<td>',"<a href=\"show_actor.php?aid_1=$aid&first_1=$first&last_1=$last&dob_1=$dob&dod_1=$dod\">$first $last</a>",'</td>';
						echo '<td>',$dob,'</td>';
						echo '</tr>';
					}
				
			echo '</tbody>';
		echo '</table>';
			
		
			if(isset($_GET['button'])) {
				if($search != "")
					$query2 = "SELECT id, year, title FROM Movie WHERE title regexp '$search' order by title, year, id;";
				else
					$query2 = "SELECT id, year, title FROM Movie order by title, year, id;";
				$res2 = $dbc->query($query2);
			}
		
		
		echo '<h3>Matching Movies:</h3>';
		echo '<table border=1 cellspacing=1 cellpadding=2>';
		echo '<thead>';
			echo '<tr align=center>';
				
					echo '<td><b><font color="blue">',"Title",'</font></b></td>';
					echo '<td><b><font color="blue">',"Year",'</font></b></td>';
				
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
				
					while($row2 = mysqli_fetch_row($res2)) {
						$mid = $row2[0];
						$year = $row2[1];
						$title = $row2[2];
						echo '<tr align=center>';
						echo '<td>',"<a href=\"show_movie.php?mid_1=$mid&title_1=$title&year_1=$year\">$title</a>",'</td>';
						echo '<td>',$year,'</td>';
						echo '</tr>';
					}
				
			echo '</tbody>';
		echo '</table>';
		
				
	
		mysqli_close($dbc);
	} // this closing bracket refers back up to the is_set() for the button so that the page looks cleaner before button press
		echo "<a href=\"main.php\">Back to Main</a><br/>";
	?>
</body>
</html>