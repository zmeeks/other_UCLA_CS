<html>
<head><title>CS143 Project 1B</title></head>
<body>
	<h2 style="color:orange;">Actor Information</h2>
	
	<?php
	
		$aid = $_GET["aid_1"];
		$first = $_GET["first_1"];
		$last = $_GET["last_1"];
		$dob = $_GET["dob_1"];
		$dod = $_GET["dod_1"];
		
		$query = "SELECT role, title, year, A.id as mid FROM Movie A inner join MovieActor B on A.id = B.mid WHERE B.aid = $aid;";
		
		$dbc = new mysqli("localhost", "cs143", "", "CS143"); //change to: "cs143", "", "CS143"
		$res = $dbc->query($query);
		
		if(is_null($dod) || $dod == "")
			echo "<h3>$first $last (".$dob."):</h3>";
		else
			echo "<h3>$first $last (".$dob." -- ".$dod."):</h3>";
			
	?>
		
		<table border=1 cellspacing=1 cellpadding=2>
			<thead>
				<tr align=center>
					<?php
						echo '<td><b><font color="blue">',"Role",'</font></b></td>';
						echo '<td><b><font color="blue">',"Title",'</font></b></td>';
						echo '<td><b><font color="blue">',"Year",'</font></b></td>';
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					while($row = mysqli_fetch_row($res)) {
						$role = $row[0];
						$title = $row[1];
						$year = $row[2];
						$mid = $row[3];
						echo '<tr align=center>';
						echo '<td>',$role,'</td>';
						echo '<td>',"<a href=\"show_movie.php?mid_1=$mid&title_1=$title&year_1=$year\">$title</a>",'</td>';
						echo '<td>',$year,'</td>';
						echo '</tr>';
					}
				?>
			</tbody>
		</table>
		<?php
		
			mysqli_close($dbc);
			echo "<a href=\"main.php\">Back to Main</a><br/>";
		
	?>
</body>
</html>