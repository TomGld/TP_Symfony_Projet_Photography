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

CREATE TABLE `projet` (
  `id` integer PRIMARY KEY,
  `name` varchar(255),
  `dateStart` datetime,
  `dateEnd` datetime,
  `description` string,
  `owner_id` int,
  `note_id` integer
);

CREATE TABLE `note` (
  `id` integer PRIMARY KEY,
  `mediaNote` int,
  `userNote` int
);

CREATE TABLE `collaborator` (
  `project_id` int,
  `user_id` int,
  PRIMARY KEY (`project_id`, `user_id`)
);

CREATE TABLE `image` (
  `id` integer PRIMARY KEY,
  `project_id` int,
  `imagePath` varchar(255),
  `user_id` int
);

ALTER TABLE `user` ADD FOREIGN KEY (`id`) REFERENCES `projet` (`owner_id`);

ALTER TABLE `image` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `projet` ADD FOREIGN KEY (`id`) REFERENCES `image` (`project_id`);

CREATE TABLE `collaborator_user` (
  `collaborator_user_id` int,
  `user_id` integer,
  PRIMARY KEY (`collaborator_user_id`, `user_id`)
);

ALTER TABLE `collaborator_user` ADD FOREIGN KEY (`collaborator_user_id`) REFERENCES `collaborator` (`user_id`);

ALTER TABLE `collaborator_user` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);


ALTER TABLE `projet` ADD FOREIGN KEY (`id`) REFERENCES `collaborator` (`project_id`);

ALTER TABLE `projet` ADD FOREIGN KEY (`note_id`) REFERENCES `note` (`id`);
