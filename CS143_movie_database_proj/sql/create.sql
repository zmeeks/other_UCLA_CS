-- note regarding year constraints: first movie ever made was made in 1878

create table Movie(
	
	id int, 
	
	title varchar(100) NOT NULL,
	
	year int NOT NULL, 
	
	rating varchar(10), 
	company varchar(50),
	
	PRIMARY KEY (id), -- Movie id as unique key
	
	CHECK (year > 1877 AND year <= year(curdate())))  -- check that year is valid 
 
ENGINE = INNODB;

create table Actor(
	
	id int, 
	
	last varchar(20) NOT NULL, 
	
	first varchar(20) NOT NULL, 
	
	sex varchar(6), 
	
	dob date NOT NULL, 
	
	dod date,
	
	PRIMARY KEY (id), -- actor id as unique key
	
	CHECK (year(dob) > 1777 AND year(dob) <= year(curdate()))) -- check that dob is valid 

ENGINE = INNODB;

create table Sales(
	
	mid int, 
	
	ticketsSold int, 
	
	totalIncome int,
	
	FOREIGN KEY (mid) REFERENCES Movie(id), -- references a primary key
	
	CHECK (ticketsSold >=0)) -- check tickets sold is non-negative
 	
ENGINE = INNODB;

create table Director(
	
	id int, 
	
	last varchar(20) NOT NULL, 
	
	first varchar(20) NOT NULL, 
	
	dob date NOT NULL, 
	
	dod date,
	
	PRIMARY KEY (id), -- irector id as unique key
	
	CHECK (year(dob) > 1777 AND year(dob) <= year(curdate()))) -- check that dob is valid 
	
ENGINE = INNODB;

create table MovieGenre(
	
	mid int, 
	
	genre varchar(20),
	
	FOREIGN KEY (mid) REFERENCES Movie(id)) -- references a primary key
	
ENGINE = INNODB;

create table MovieDirector(
	
	mid int, 
	
	did int,
	
	FOREIGN KEY (did) REFERENCES Director(id), -- references a primary key
	
	FOREIGN KEY (mid) REFERENCES Movie(id)) -- references a primary key
	
ENGINE = INNODB;

create table MovieActor(
	
	mid int, 
	
	aid int, 
	
	role varchar(50),
	
	FOREIGN KEY (aid) REFERENCES Actor(id), -- references a primary key
	
	FOREIGN KEY (mid) REFERENCES Movie(id)) -- references a primary key
	
ENGINE = INNODB;

create table MovieRating(
	
	mid int, 
	
	imdb int, 
	
	rot int,
	
	FOREIGN KEY (mid) REFERENCES Movie(id)) -- references a primary key
	
ENGINE = INNODB;

create table Review(
	
	name varchar(20), 
	
	time TIMESTAMP, 
	
	mid int, 
	
	rating int, 
	
	comment varchar(500),
	
	FOREIGN KEY (mid) REFERENCES Movie(id)) -- references a primary key
	
ENGINE = INNODB;

create table MaxPersonID(id int) ENGINE = INNODB;

create table MaxMovieID(id int) ENGINE = INNODB;
