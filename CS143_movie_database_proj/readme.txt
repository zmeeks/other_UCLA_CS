*********README*********
------------------------
--Zack Meeks XXXXXX115--
------------------------

The main page for my movie database website is main.php

Names such as “J’son Lee” are made acceptable names in the database by using the object oriented mysqli function real_escape_string()

The most challenging/rewarding part of the website was figuring out how to make the add actor to movie relation better than the one shown in the demo site.  Solving that hurdle really showed me how php forms work.

Instead of having a separate column for date of death, if the database has info of an actor’s or director’s death it will use the format (dob — dod), otherwise it will just show (dob).

One of the database ratings is “Surrendere”.  I looked this up and the real name is “Surrendered”, just the amount of characters cuts the last letter off.  Note: I show on the page ‘Add Movie’ this rating as“Surrendered”, but I query the database using only the letters “Surrendere”. 
