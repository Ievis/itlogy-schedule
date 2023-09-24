CREATE TABLE `users`
(
    `user_id`         int                                     NOT NULL AUTO_INCREMENT,
    `email`      varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL UNIQUE,
    `first_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
    `last_name`  varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
    `surname`    varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
    `phone`      varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
    `role`       varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `schedules`
(
    `schedule_id`         int      NOT NULL AUTO_INCREMENT,
    `teacher_id` int DEFAULT NULL,
    `student_id` int DEFAULT NULL,
    `will_at`    datetime NOT NULL,
    PRIMARY KEY (`schedule_id`),
    CONSTRAINT `FK_ScheduleTeacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`),
    CONSTRAINT `FK_ScheduleStudent` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `courses`
(
    `course_id`   int                                     NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
    PRIMARY KEY (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `courses_users`
(
    `user_id`   int NOT NULL,
    `course_id` int NOT NULL,
    PRIMARY KEY (`user_id`, `course_id`),
    CONSTRAINT `FK_Courses` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
    CONSTRAINT `FK_Users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;