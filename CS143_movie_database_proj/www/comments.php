<html>
<head><title>CS143 Project 1B</title></head>
<body>
	<h2 style="color:orange;">CS 143 Movie Database</h2>
	<?php
		$mid = $_GET["mid_1"];
		$year = $_GET["year_1"];
		$dbc = new mysqli("localhost", "cs143", "", "CS143");
		$query0 = "SELECT title from Movie where id=$mid;";
		$res0 = $dbc->query($query0);
		$row0 =  mysqli_fetch_row($res0);
		$title = $row0[0];
		echo '<p><b>Add new comment for ',$title,':</b></p><br/>';
		mysqli_close($dbc);
	?>
	<form action="comments.php" method="GET">
		Your Name:</br>
		<textarea name="name" cols="20" rows="1"><?php echo $_GET["name"];?></textarea><br><br>
		Comment:</br>
		<textarea name="comment" cols="80" rows="6"></textarea><br><br/>
		Rating:
		<input type="radio" name="rating" value=1> 1 &nbsp; &nbsp;
		<input type="radio" name="rating" value=2> 2 &nbsp; &nbsp;
		<input type="radio" name="rating" value=3> 3 &nbsp; &nbsp;
		<input type="radio" name="rating" value=4> 4 &nbsp; &nbsp;
 		<input type="radio" name="rating" value=5> 5 <br>
 		<input type="submit" name="pressed" value="Add Comment">
        <input type="hidden" name="mid_1" value=<?php echo $mid; ?>>
        <input type="hidden" name="year_1" value=<?php echo $year; ?>>
	</form>
	<?php
		if(isset($_GET['pressed'])) {
			$mid = $_GET["mid_1"];
			$year = $_GET["year_1"];
			$name = $_GET["name"];
			$comment = $_GET["comment"];
			$rating = $_GET["rating"];
			$dbc = new mysqli("localhost", "cs143", "", "CS143");
			$query1 = "SELECT title from Movie where id=$mid;";
			$res1 = $dbc->query($query1);
			$row1 =  mysqli_fetch_row($res1);
			$title = $row0[0];
			$name = $dbc->real_escape_string($name);
			$comment = $dbc->real_escape_string($comment);
			
			
			$dbc->query("INSERT INTO Review VALUES('$name', now(), $mid, $rating, '$comment');");
			mysqli_close($dbc);
	}
		echo "<a href=\"show_movie.php?mid_1=$mid&title_1=$title&year_1=$year\">Back to Movie info</a><br/>";
		echo "<a href=\"main.php\">Back to Main</a><br/>";
	?>
</body>
</html>

