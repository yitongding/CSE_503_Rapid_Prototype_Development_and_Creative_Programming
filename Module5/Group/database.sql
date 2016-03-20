create database module5;

CREATE TABLE users(
	user_id				SMALLINT UNSIGNED	AUTO_INCREMENT,
	user_name			VARCHAR(20)			NOT NULL,
	user_slated_pw		VARCHAR(50)			NOT NULL,
	PRIMARY KEY (user_id),
	UNIQUE KEY (user_name)
);

CREATE TABLE events(
    event_id    INT UNSIGNED    AUTO_INCREMENT,
    event_user_id SMALLINT UNSIGNED,
    event_title VARCHAR(100)    NOT NULL,
    event_date  DATE NOT NULL,
    event_tag   VARCHAR(5)  DEFAULT 'ALL', 
    PRIMARY KEY (event_id),
    FOREIGN KEY (event_user_id) REFERENCES users(user_id)
);

CREATE TABLE share(
    share_id    SMALLINT UNSIGNED AUTO_INCREMENT,
    share_user1_id  SMALLINT UNSIGNED,
    share_user2_id  SMALLINT UNSIGNED,
    PRIMARY KEY (share_id),
    FOREIGN KEY (share_user1_id) REFERENCES users(user_id),
    FOREIGN KEY (share_user2_id) REFERENCES users(user_id)
);