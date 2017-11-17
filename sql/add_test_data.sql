-- Lisää INSERT INTO lauseet tähän tiedostoon

INSERT INTO Users (name, password, motto, datum) VALUES 
	('Tom', '1234', 'I am your God', '10.11.2017');
INSERT INTO Users (name, password, motto, datum) VALUES 
	('Abel', '1234', 'I serve you.', '10.11.2017');
INSERT INTO Users (name, password, motto, datum) VALUES 
	('Beni', '1234', 'Your wish is my command.', '10.11.2017');
INSERT INTO Users (name, password, motto, datum) VALUES 
	('Calle', '1234', 'I am prepared.', '10.11.2017');
INSERT INTO Users (name, password, motto, datum) VALUES 
	('Dani', '1234', 'I am not a number.', '10.11.2017');

INSERT INTO Computers (brand, name, infotext, imgurl) VALUES 
	('Commodore', '64', 'Tasavallan tietokone.', 'http://oldcomputers.net/pics/C64-left.jpg');
INSERT INTO Computers (brand, name, infotext, imgurl) VALUES 
	('Commodore', 'Amiga 1000', 'The start of a revolution.', 'http://oldcomputers.net/pics/A1000.jpg');
INSERT INTO Computers (brand, name, infotext, imgurl) VALUES 
	('Commodore', 'Amiga 2000', 'Expand me, Zorro.', 'http://oldcomputers.net/pics/A2000-1.jpg');
INSERT INTO Computers (brand, name, infotext, imgurl) VALUES 
	('Commodore', 'Amiga 500', 'Set the bedrooms on fire!', 'http://oldcomputers.net/pics/amiga500.jpg');

INSERT INTO Logs (user_id, comp_id, datum) VALUES 
	(1, 1, '10.11.2017');
INSERT INTO Logs (user_id, comp_id, datum) VALUES 
	(4, 2, '10.11.2017');
INSERT INTO Logs (user_id, comp_id, datum) VALUES 
	(3, 3, '10.11.2017');
INSERT INTO Logs (user_id, comp_id, datum) VALUES 
	(2, 4, '10.11.2017');

INSERT INTO Reviews (user_id, comp_id, review, rating, datum) VALUES 
	(1, 1, 'Paras 8-bittinen!', 5, '10.11.2017');
INSERT INTO Reviews (user_id, comp_id, review, rating, datum) VALUES 
	(1, 2, 'Saavuttamaton unelma!', 5, '10.11.2017');
INSERT INTO Reviews (user_id, comp_id, review, rating, datum) VALUES 
	(5, 3, 'Iso ja kömpelö, ruma', 3, '10.11.2017');
INSERT INTO Reviews (user_id, comp_id, review, rating, datum) VALUES 
	(1, 4, 'Paras tietokone ikinä!', 5, '10.11.2017');
INSERT INTO Reviews (user_id, comp_id, review, rating, datum) VALUES 
	(3, 4, 'Amigaaaaaaa!!!!', 5, '10.11.2017');

