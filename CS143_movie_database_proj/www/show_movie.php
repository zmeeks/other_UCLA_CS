<html>
<head><title>CS143 Project 1B</title></head>
<body>
	<h2 style="color:orange;">Movie Information</h2>
	
	<?php
	
		$mid = $_GET["mid_1"];
		$title = $_GET["title_1"];
		$year = $_GET["year_1"];
		
		$query1 = "SELECT rating, company, year FROM Movie where id = $mid;";
		$query2 = "SELECT genre from MovieGenre where mid = $mid order by genre;";
		$query3 = "SELECT B.role, A.first, A.last, A.id, A.dob, A.dod FROM Actor A inner join MovieActor B on A.id = B.aid WHERE B.mid = $mid order by A.last, A.first, A.dob;";
		$query4 = "SELECT concat(A.first, ' ', A.last), A.dob, A.dod FROM Director A inner join MovieDirector B on A.id = B.did WHERE B.mid = $mid order by A.last, A.first, A.dob;";
		$query5 = "SELECT name, time, rating, comment from Review where mid = $mid order by time;";
		$query6 = "SELECT avg(rating) from Review group by mid having mid = $mid;";
		
		$dbc = new mysqli("localhost", "cs143", "", "CS143"); 
		
		$res1 =  $dbc->query($query1);
		$res2 =  $dbc->query($query2);
		$res3 =  $dbc->query($query3);
		$res4 =  $dbc->query($query4);
		$res5 =  $dbc->query($query5);
		$res6 =  $dbc->query($query6);
		
		$row1 = mysqli_fetch_row($res1);
		$row6 = mysqli_fetch_row($res6);
		
		
		echo "<h3><b>".$title."(".$year.") :</b></h3><br>";
		echo "Producer: ".$row1[1]."<br>";
		echo "MPAA Rating: ".$row1[0]."<br>";
		echo "Director: ";
		while($row4 = mysqli_fetch_row($res4)){
			if(is_null($row4[2]) || $row4[2] == "")
				echo $row4[0]." (".$row4[1].")  ";
			else
				echo $row4[0]." (".$row4[1]." -- ".$row4[2].")  ";
		}
		echo "<br>";
		echo "Genre(s): ";
		while($row2 = mysqli_fetch_row($res2))
			echo $row2[0]." ";
		echo "<br>";
		
		echo "<h3>Actors:</h3><br>";		
	?>
		
		<table border=1 cellspacing=1 cellpadding=2>
			<thead>
				<tr align=center>
					<?php
						echo '<td><b><font color="blue">',"Name",'</font></b></td>';
						echo '<td><b><font color="blue">',"Role",'</font></b></td>';
					?>
				</tr>
			</thead>
			<tbody>
				<?php
					while($row3 = mysqli_fetch_row($res3)) {
						$role = $row3[0];
						$first = $row3[1];
						$last = $row3[2];
						$aid = $row3[3];
						$dob = $row3[4];
						$dod = $row3[5];
						echo '<tr align=center>';
						echo '<td>'."<a href=\"show_actor.php?aid_1=$aid&first_1=$first&last_1=$last&dob_1=$dob&dod_1=$dod\">$first $last</a>".'</td>';
						echo '<td>'.$role.'</td>';
						echo '</tr>';
					}
				?>
			</tbody>
		</table>
		<?php
			
			echo "Average User Rating: ".$row6[0]."<br>";
			echo "<p><b>Comments: </b></p><br>";
			$row5 = mysqli_fetch_row($res5);
			if($row5 == "")
				echo "No user reviews yet... <br>";
			else{
				do{
					echo $row5[0]." at ",$row5[1]," rated this movie as ".$row5[2]." out of 5.<br>";
					echo "comment: <br>".$row5[3]."<br><br>"; 		
				} while($row5 = mysqli_fetch_row($res5));
			}
					
			echo '<td>'."<a href=\"comments.php?mid_1=$mid&title_1=$title&year_1=$year\">Add comment/rating here</a>".'</td>';
			echo "<br><br><br><a href=\"main.php\">Back to Main</a><br/>";
		
	?>
</body>
</html>

