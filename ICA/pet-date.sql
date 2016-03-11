/*SQL to create database for module3*/
CREATE DATABASE petbd;

CREATE TABLE pets(
	id				    SMALLINT UNSIGNED	NOT NULL AUTO_INCREMENT,
	species			    ENUM('cat','dog','fish','bird','hamster')			NOT NULL,
    name VARCHAR(50) NOT NULL,
    filename VARCHAR(150) NOT NULL,
    weight DECIMAL(4,2) NOT NULL,
    description TINYTEXT,
	PRIMARY KEY (id)
);

