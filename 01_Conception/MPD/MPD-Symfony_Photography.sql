CREATE TABLE `user` (
  `id` integer PRIMARY KEY,
  `email` varchar(255),
  `password` varchar(255),
  `fistname` varchar(255),
  `lastname` varchar(255),
  `age` datetime,
  `city` varchar(255),
  `country` varchar(255),
  `role` varchar(255)
);

CREATE TABLE `project` (
  `id` integer PRIMARY KEY,
  `name` varchar(255),
  `dateStart` datetime,
  `dateEnd` datetime,
  `description` string,
  `owner_id` int,
  `note_id` integer,
  `collaborator` int
);

CREATE TABLE `note` (
  `id` integer PRIMARY KEY,
  `mediaNote` int,
  `userNote` int
);

CREATE TABLE `image` (
  `id` integer PRIMARY KEY,
  `project_id` int,
  `imagePath` varchar(255),
  `user_id` int
);

ALTER TABLE `image` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `project` ADD FOREIGN KEY (`id`) REFERENCES `image` (`project_id`);

ALTER TABLE `project` ADD FOREIGN KEY (`note_id`) REFERENCES `note` (`id`);

CREATE TABLE `user_project` (
  `user_id` integer,
  `project_collaborator` int,
  PRIMARY KEY (`user_id`, `project_collaborator`)
);

ALTER TABLE `user_project` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `user_project` ADD FOREIGN KEY (`project_collaborator`) REFERENCES `project` (`collaborator`);


ALTER TABLE `user` ADD FOREIGN KEY (`id`) REFERENCES `project` (`owner_id`);
