create table users (
  id int not null auto_increment primary key,
  name varchar(255),
  email varchar(255) unique,
  password varchar(255),
  icon varchar(255) default 'monika.jpg',
  Nickname varchar(255),
  firstPerson varchar(30) default "私",
  NoteDays int default 0,
  KeepNoteDays int default 0,
  HighScoreNoteDays int default 0,
  created datetime
);

-- alter table users modify icon varchar(255) default 'myPage.png';

create table myDiary(
  id int not null auto_increment primary key,
  userId int,
  date varchar(50),
  good_1 varchar(450),
  good_2 varchar(450),
  good_3 varchar(450),
  other text
);

create table serif(
  id int not null auto_increment primary key,
  genre varchar(90),
  face varchar(255),
  key1 varchar(90),
  key2 varchar(90),
  key3 varchar(90),
  serif varchar(420)
);

insert into users(name, email) values('サンプル太郎', 'sample@sample.com');


insert into myDiary(userId, good_1, good_2, good_3, other, date)
values(':id', '朝ごはんが美味しかった', '空が晴れていた', '目覚めがスッキリだった', '', '2020-07-03');

insert into myDiary(userId, good_1, good_2, good_3) select good_1, good_2, good_3 from myDiary where userId = 13;
