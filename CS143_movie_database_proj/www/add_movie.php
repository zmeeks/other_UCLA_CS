<html>
<head><title>CS143 Project 1B</title></head>
<body>
	<h2 style="color:orange;">CS 143 Movie Database</h2>
	<h3>Add Movie: </h3>
	<form action="add_movie.php" method="GET">
		<p><b>Title</b></p> 
		<textarea name="title" cols="42" rows="1"></textarea><br/>
		<p><b>Year</b></p>
		<textarea name="year" cols="4" rows="1"></textarea><br><br/>
		<p><b>Company</b></p>
 		<textarea name="company" cols="42" rows="1"></textarea><br/>
 		<p><b>Rating</b></p>
		<input type="radio" name="rating" value="G"> G &nbsp; &nbsp;
		<input type="radio" name="rating" value="PG"> PG &nbsp; &nbsp;
		<input type="radio" name="rating" value="PG-13"> PG-13 &nbsp; &nbsp;
		<input type="radio" name="rating" value="R"> R &nbsp; &nbsp;
		<input type="radio" name="rating" value="NC-17"> NC-17 &nbsp; &nbsp;
 		<input type="radio" name="rating" value="surrendere"> Surrendered <br>
 		<p><b>Genre</b></p>
 		<input type="checkbox" id="Action" value="Action" name="genres[]"><label for="Action"> Action &emsp;</label> 
 		<input type="checkbox" id="Adult" value="Adult" name="genres[]"><label for="Adult"> Adult &emsp;</label> 
 		<input type="checkbox" id="Adventure" value="Adventure" name="genres[]"><label for="Adventure"> Adventure &emsp;</label>
 		<input type="checkbox" id="Animation" value="Animation" name="genres[]"><label for="Animation"> Animation</label><br> 
 		<input type="checkbox" id="Comedy" value="Comedy" name="genres[]"><label for="Comedy"> Comedy &emsp;</label>
 		<input type="checkbox" id="Crime" value="Crime" name="genres[]"><label for="Crime"> Crime &emsp;</label>
 		<input type="checkbox" id="Documentary" value="Documentary" name="genres[]"><label for="Documentary"> Documentary &emsp;</label> 
 		<input type="checkbox" id="Drama" value="Drama" name="genres[]"><label for="Drama"> Drama</label><br> 
 		<input type="checkbox" id="Family" value="Family" name="genres[]"><label for="Family"> Family &emsp;</label> 
 		<input type="checkbox" id="Fantasy" value="Fantasy" name="genres[]"><label for="Fantasy"> Fantasy &emsp;</label> 
 		<input type="checkbox" id="Horror" value="Horror" name="genres[]"><label for="Horror"> Horror &emsp;</label> 
 		<input type="checkbox" id="Musical" value="Musical" name="genres[]"><label for="Musical"> Musical</label><br>
 		<input type="checkbox" id="Mystery" value="Mystery" name="genres[]"><label for="Mystery"> Mystery &emsp;</label> 
 		<input type="checkbox" id="Romance" value="Romance" name="genres[]"><label for="Romance"> Romance &emsp;</label> 
 		<input type="checkbox" id="Sci-Fi" value="Sci-Fi" name="genres[]"><label for="Sci-Fi"> Sci-Fi &emsp;</label> 
 		<input type="checkbox" id="Short" value="Short" name="genres[]"><label for="Short"> Short</label><br> 
 		<input type="checkbox" id="Thriller" value="Thriller" name="genres[]"><label for="Thriller"> Thriller &emsp;</label>
 		<input type="checkbox" id="War" value="War" name="genres[]"><label for="War"> War &emsp;</label>
 		<input type="checkbox" id="Western" value="Western" name="genres[]"><label for="Western"> Western</label><br><br>
 	
 		<input type="submit" name="pressed" value="Add!!!">
	</form>
	
	<form>  </form> 


	<?php
		if(isset($_GET['pressed'])) {
		$title = $_GET["title"];
		$year = $_GET["year"];
		$company = $_GET["company"];
		$rating = $_GET["rating"];
		$film_genres = $_GET["genres"];
		$num_genres = 0;
		if(empty($film_genres))  
			echo "<i>No film genres set.</i>" ; 
		else 
			$num_genres = count($film_genres);
		
		$dbc = new mysqli("localhost", "cs143", "", "CS143");
		if(!$dbc)
			echo "Connection failed!<br/>";
		$dbc->query("UPDATE MaxMovieID SET id = id + 1;");
		
		$title = $dbc->real_escape_string($title);
		$company = $dbc->real_escape_string($company);
		
		$res = $dbc->query("SELECT id FROM MaxMovieID;");
		$row = mysqli_fetch_row($res);
		$new_id = $row[0];
		$query = "INSERT INTO Movie VALUES($new_id, '$title', $year, '$rating', '$company');";
		$dbc->query($query);
		for($i=0; $i < $num_genres; $i++){
			$genre = $film_genres[$i];
			$dbc->query("INSERT INTO MovieGenre VALUES($new_id, '$genre');");
		}
		mysqli_close($dbc);
		}
		echo "<a href=\"main.php\">Back to Main</a><br/>";
	?>
</body>
</html>