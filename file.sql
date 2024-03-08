drop table if exists `films`;

create table if not exists `films`(
	`id` INT unsigned not null auto_increment,
	`name` VARCHAR(255),
	`year_out` SMALLINT,
	`duration` SMALLINT unsigned comment 'minutes',
	`studio` VARCHAR(255),
	`genre` VARCHAR(255),
	`budget` DECIMAL(12, 2) unsigned not null default 0.00 comment 'until 9_999_999_999.99 dollars',
	`box_office` DECIMAL(12, 2) unsigned not null default 0.00 comment 'until 9_999_999_999.99 dollars',
	`director` VARCHAR(255),
	`screenwriter` VARCHAR(255),
	`country` VARCHAR(100),
	`language` CHAR(2),
	primary key(`id`)
);

insert into `films`(name, year_out, duration, studio, genre, budget, box_office, director, screenwriter, country, language) values
('The Rise of Danny', 2024, 180, 'Shelby Limited', 'драма, биография, детектив', 100000000, 4365324258, 'Томми Ю. Шелбников', 'Вэнни. С. Помог Ай', 'ЮАР, Конго, Германия, Россия', 'en'),
('Nikolai: Absolute Evil', 2027, 195, 'Shelby Limited', 'драма, триллер, биография', 200000000, 5485295091, 'Стивен Спилберг', 'Томми Ю. Шелбников', 'Германия, Франция, Швеция, Россия', 'en'),
('Maxy & Danny: together forever!', 2030, 114, 'Shelby Limited', 'комедия, драма, слэшер', 125000000, 985345984, 'Вэнни. С. Помог Ай', 'Квентин Тарантино', 'Россия', 'en'),
('Зелёная миля', 1999, 189, 'Castle Rock Entertainment', 'фэнтезийная драма', 60000000, 286801374, 'Фрэнк Дарабонт', 'Фрэнк Дарабонт', 'США', 'en'),
('The Shawshank Redemption', 1994, 142, 'Castle Rock Entertainment', 'драма', 25000000, 73300000, 'Фрэнк Дарабонт', 'Фрэнк Дарабонт', 'США', 'en'),
('Memento', 2000, 113, 'Summit Entertainment', 'детектив, триллер, драма', 9000000, 39665951, 'Кристофер Нолан', 'Джонатан Нолан', 'США', 'en');

