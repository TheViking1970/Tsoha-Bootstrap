-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE Users(
	id SERIAL PRIMARY KEY,
	name varchar(50) NOT NULL,
	password char(32) NOT NULL,
	motto varchar(1024),
	datum TIMESTAMP NOT NULL
);

CREATE TABLE Computers(
	id SERIAL PRIMARY KEY,
	brand varchar(50) NOT NULL,
	name varchar(50) NOT NULL,
	imgurl varchar(255),
	infotext varchar(8192) NOT NULL
);

CREATE TABLE Logs(
	id SERIAL PRIMARY KEY,
	comp_id INTEGER REFERENCES Computers(id) ON DELETE CASCADE, 
	user_id INTEGER REFERENCES Users(id) ON DELETE CASCADE,
	datum TIMESTAMP NOT NULL
);

CREATE TABLE Reviews(
	id SERIAL PRIMARY KEY,
	user_id INTEGER REFERENCES Users(id) ON DELETE CASCADE,
	comp_id INTEGER REFERENCES Computers(id) ON DELETE CASCADE,
	review varchar(2048) NOT NULL,
	rating INTEGER NOT NULL,
	datum TIMESTAMP NOT NULL
);

