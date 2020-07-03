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

-- alter table users modify icon varchar(255) default 'monika.jpg';

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

insert into serif(genre, face, key1, serif)
values(
  "トップページ",
  "lara.png",
  "朝",
  "＜二人称＞、おはようございます！昨晩はよく眠れましたか？\r今日も一日、頑張りましょう！"
);
insert into serif(genre,face,key1,serif)
values(
  "トップページ",
  "lara_normal.png",
  "昼",
  "＜二人称＞、こんにちは！\r本日はいかがお過ごしですか？楽しいことがたくさんあるといいですね！"
);
insert into serif(genre,face,key1,serif)
values(
  "トップページ",
  "lara_smile.png",
  "夜",
  "＜二人称＞、こんばんは！\n今日も一日、お疲れ様です！よく頑張りましたね！"
);

-- 日記閲覧ページ
insert into serif(genre,face,serif)
values(
  "日記閲覧ページ",
  "lara_smile.png",
  "この日は、こんなに良いことがありましたよ！"
);
insert into serif(genre,face,serif)
values(
  "日記閲覧ページ",
  "lara_normal.png",
  "これらの記録は、＜二人称＞が生きてきた軌跡です。\n大切にしていきたいですね！"
);
insert into serif(genre,face,serif)
values(
  "日記閲覧ページ",
  "lara.png",
  "＜二人称＞は、毎日を懸命に生きています。\r日記というのは、その偉業を残し、振り返るための良い手段なのです！"
);
insert into serif(genre, face, key1, serif)
values(
  "日記閲覧ページ",
  "lara.png",
  "yetEdit",
  "今日はまだ、日記を書いていないようですね！\rもしよろしければ、どんな良いことがあったのか、＜一人称＞に教えていただけませんか？"
);
insert into serif(genre, face, key1, serif)
values(
  "日記閲覧ページ",
  "lara.png",
  "doneEdit",
  "今日は、こんなことがあったのですね！\r素晴らしい！\rこの出来事はきっと、＜二人称＞の未来を彩るでしょう！"
);
insert into serif(genre, face, key1, key2, serif)
values(
  "日記閲覧ページ",
  "lara.png",
  "doneEdit",
  "1",
  "初めての日記投稿です！最初の一歩を踏み出したということですね！\r偉いっ！"
);
insert into serif(genre, face, key1, key2, serif)
values(
  "日記閲覧ページ",
  "lara.png",
  "doneEdit",
  "3",
  "なんと、今日で三日連続も日記をつけています！すごいっ！\rこれからも続けてくださると、＜一人称＞は嬉しいです！"
);
insert into serif(genre, face, key1, key2, serif)
values(
  "日記閲覧ページ",
  "lara.png",
  "doneEdit",
  "7",
  "なんとなんと、今日で一週間連続で日記をつけたことになります！\r一週間ですよ、一週間！＜二人称＞、凄すぎます！"
);




-- 日記編集ページ
insert into serif(genre,face,serif)
values(
  "日記編集ページ",
  "lara.png",
  "今日はどんな一日でしたか？\r楽しかったこと、嬉しかったこと、きっと少しでもあるはずです！\rそれらを記録しちゃいましょう！"
);



-- マイページセリフ
insert into serif(genre,face,serif)
values(
  "マイページ",
  "lara_normal.png",
  "ここでは、＜二人称＞に関する情報を確認できます！"
);
insert into serif(genre,face,serif)
values(
  "マイページ",
  "lara_normal.png",
  "＜二人称＞の記録を確認しましょう！"
);
insert into serif(genre,face,serif)
values(
  "マイページ",
  "lara_normal.png",
  "…………zzz"
);
insert into serif(genre,face,serif)
values(
  "マイページ",
  "lara.png",
  "突然ですが……＜二人称＞、牛丼はお好きですか？\r＜一人称＞は大好きなので、ほぼ毎日食べています！"
);
insert into serif(genre,face,serif)
values(
  "マイページ",
  "lara.png",
  "＜二人称＞、生きるって何ですか？また、死ぬって何ですか？\r＜一人称＞には、わかりません"
);
insert into serif(genre, face, key1, serif)
values(
  "マイページ",
  "lara.png",
  3,
  "＜二人称＞！今日で合計３日、日記を書いていますね！\rこの調子で続けていきましょう！"
);

insert into serif(genre, face, key1, serif)
values(
  "マイページ",
  "lara.png",
  5,
  "＜二人称＞！今日で合計５日、日記を書いていますよ！\r幸せな出来事が５日も！せっかくなので、日記を見返してみてはいかがですか？"
);

insert into serif(genre, face, key1, serif)
values(
  "マイページ",
  "lara.png",
  10,
  "＜二人称＞！今日で合計１０日、日記を書いています！\r１０日も日記をつけるなんて、凄すぎます！\rこの調子でどんどん、幸せの記録を残しましょう！"
);




insert into serif(genre,face,serif)
values(
  "マイページ編集ページ",
  "lara_normal.png",
  "ここでは、＜二人称＞に関する情報を編集することができますよ！"
);