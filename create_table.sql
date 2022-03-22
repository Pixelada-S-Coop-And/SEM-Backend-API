
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `game_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT 1,
   PRIMARY KEY(id),
   FOREIGN KEY (game_id) REFERENCES game_comments(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT 1,
  `date` DATETIME NOT NULL,
  `image` varchar(300) NOT NULL,
  `pause_time` int(11) NOT NULL DEFAULT 10,
  `solution` varchar(100) NOT NULL,
  `latitude` float(20) NOT NULL,
  `longitude` float(20) NOT NULL,
  `win_comment_id` int(11) DEFAULT NULL,
  `wrong_comment_id` int(11) DEFAULT NULL,
  `gps_close_comment_id` int(11) DEFAULT NULL,
  `gps_far_comment_id` int(11) DEFAULT NULL,
  `lose_comment_id` int(11) DEFAULT NULL,
  `final_comment_id` int(11) DEFAULT NULL,
   PRIMARY KEY(id),
   FOREIGN KEY (win_comment_id) REFERENCES game_comments(id),
   FOREIGN KEY (wrong_comment_id) REFERENCES game_comments(id),
   FOREIGN KEY (gps_close_comment_id) REFERENCES game_comments(id),
   FOREIGN KEY (gps_far_comment_id) REFERENCES game_comments(id),
   FOREIGN KEY (lose_comment_id) REFERENCES game_comments(id),
   FOREIGN KEY (final_comment_id) REFERENCES game_comments(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `game_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `text` varchar(300) NOT NULL,
  `media` varchar(300) NOT NULL,
   PRIMARY KEY(id),
   FOREIGN KEY (comment_id) REFERENCES game_comments(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `game_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `answer` varchar(300) NOT NULL,
  `result` int(1) NOT NULL DEFAULT 0,
  `comment_id` int(11) NOT NULL,
   PRIMARY KEY(id),
   FOREIGN KEY (game_id) REFERENCES games(id),
   FOREIGN KEY (comment_id) REFERENCES game_comments(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `user_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `date` DATETIME NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
   PRIMARY KEY(id),
   FOREIGN KEY (user_id) REFERENCES users(id),
   FOREIGN KEY (game_id) REFERENCES games(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `user_friends` (
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
   FOREIGN KEY (user_id) REFERENCES users(id),
   FOREIGN KEY (friend_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;