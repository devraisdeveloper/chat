TTU - Software developer position's test tasks
============================

Description
 

Palun tee LAMP (WAMP jne) tehnoloogiatel lihtne veebipõhine jututuba (chatroom), kus

kasutajad saavad vestelda ja kõik näevad kõigi postitusi.

 

Minimaalne funktsionaalsus võiks olla sisse/välja logimine, sõnumite kirjutamine, nendele

vastamine, teiste sõnumid võiks kohe nähtavale ilmuda, ilma et kasutaja peaks refreshi tegema (xhr meetod).

 

Kasuta jquery, ajax, php ilma mingi frameworkita (html mallide jaoks võib kasutada omal

valikul mõnda template engine).

 

Andmebaasiks kasuta mysql. Pööra tähelepanu ka turvalisusele. 


### Create tables

```sql

CREATE TABLE `chat_users` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(100) NOT NULL,
	`password` VARCHAR(150) NOT NULL,
	`username` VARCHAR(45) NOT NULL,
	`user_status` TINYINT(1) NOT NULL,
	`first_name` VARCHAR(45) NOT NULL,
	`second_name` VARCHAR(45) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `username_UNIQUE` (`username`),
	UNIQUE INDEX `email_UNIQUE` (`email`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

CREATE TABLE `chat_messages` (
	`chat_id` INT(11) NOT NULL AUTO_INCREMENT,
	`chat_user_id` INT(11) NOT NULL,
	`chat_message` TEXT NOT NULL,
	`chat_time` DATETIME NOT NULL,
	`username` VARCHAR(45) NOT NULL,
	PRIMARY KEY (`chat_id`),
	INDEX `chat_user_id` (`chat_user_id`),
	CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`chat_user_id`) REFERENCES `chat_users` (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;


Important
---------------------

Open signup.php and register yourself

My url was http://localhost/chat/signup.php

Chat is powered by ajax. You can see offline and online users and see all the previous posts.


Very Important
---------------------

I added a captcha for signup. But today it stopped working. it went to an endless lope.
So I commented out the captcha code. It used to work. Strange it is google official capycha. The code for it is in 
backend.php Starting from line 78.

I added functionality so that if you close the browser your status will be "non-active"
But the solution I used worked when you refreshed the page. So it looked like you logged out.
I comment it out. but you can use it if you want to.

It is in chat.php Starting line 124

