/*SQL to create database for module3*/

CREATE TABLE users(
	user_id				SMALLINT UNSIGNED	AUTO_INCREMENT,
	user_name			VARCHAR(20)			NOT NULL,
	user_slated_pw		VARCHAR(50)			NOT NULL,
	PRIMARY KEY (user_id),
	UNIQUE KEY (user_name)
);


CREATE TABLE news(
	news_id				SMALLINT UNSIGNED	AUTO_INCREMENT,
	news_title			VARCHAR(100)		NOT NULL,
	news_author_id		SMALLINT UNSIGNED,
	/* ^ will be linked as foreign key*/
	news_content		VARCHAR(1000)		NOT NULL,
	news_submit_time	DATETIME			NOT NULL,
	news_timestamp		TIMESTAMP,
	/* ^ auto change when edit*/
	PRIMARY KEY (news_id),
	FOREIGN KEY news_author_id REFERENCES users(user_id)
);

CREATE TABLE comments(
	comment_id			MEDIUMINT UNSIGNED	AUTO_INCREMENT,
	comment_author_id	SMALLINT UNSIGNED,
	/* ^ will be linked as foreign key*/
	comment_news_id		SMALLINT UNSIGNED,
	/* ^ will be linked as foreign key*/
	comment_content		VARCHAR(140)		NOT NULL,
	comment_timestamp	TIMESTAMP,
	PRIMARY KEY (comment_id),
	FOREIGN KEY (comment_author_id) REFERENCES users(user_id),
	FOREIGN KEY (comment_news_id)	REFERENCES news(news_id)
);

CREATE TABLE links(
	link_id				SMALLINT UNSIGNED	AUTO_INCREMENT,
	link_adr			VARCHAR(120)		NOT NULL,
	link_news_id		SMALLINT UNSIGNED,
	PRIMARY KEY (link_id),
	FOREIGN KEY (link_news_id)	REFERENCES news(news_id)
);
