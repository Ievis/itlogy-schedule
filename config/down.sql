set @var=if((SELECT true FROM information_schema.TABLE_CONSTRAINTS WHERE
            CONSTRAINT_SCHEMA = DATABASE() AND
            TABLE_NAME        = 'schedules' AND
            CONSTRAINT_NAME   = 'FK_ScheduleTeacher' AND
            CONSTRAINT_TYPE   = 'FOREIGN KEY') = true,'ALTER TABLE schedules
            drop foreign key FK_ScheduleTeacher','select 1');

prepare stmt from @var;
execute stmt;
deallocate prepare stmt;

set @var=if((SELECT true FROM information_schema.TABLE_CONSTRAINTS WHERE
            CONSTRAINT_SCHEMA = DATABASE() AND
            TABLE_NAME        = 'schedules' AND
            CONSTRAINT_NAME   = 'FK_ScheduleStudent' AND
            CONSTRAINT_TYPE   = 'FOREIGN KEY') = true,'ALTER TABLE schedules
            drop foreign key FK_ScheduleStudent','select 1');

prepare stmt from @var;
execute stmt;
deallocate prepare stmt;

set @var=if((SELECT true FROM information_schema.TABLE_CONSTRAINTS WHERE
            CONSTRAINT_SCHEMA = DATABASE() AND
            TABLE_NAME        = 'courses_users' AND
            CONSTRAINT_NAME   = 'FK_Courses' AND
            CONSTRAINT_TYPE   = 'FOREIGN KEY') = true,'ALTER TABLE courses_users
            drop foreign key FK_Courses','select 1');

prepare stmt from @var;
execute stmt;
deallocate prepare stmt;

set @var=if((SELECT true FROM information_schema.TABLE_CONSTRAINTS WHERE
            CONSTRAINT_SCHEMA = DATABASE() AND
            TABLE_NAME        = 'courses_users' AND
            CONSTRAINT_NAME   = 'FK_Users' AND
            CONSTRAINT_TYPE   = 'FOREIGN KEY') = true,'ALTER TABLE courses_users
            drop foreign key FK_Users','select 1');

prepare stmt from @var;
execute stmt;
deallocate prepare stmt;

DROP TABLE IF EXISTS `schedules`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `courses_users`;
DROP TABLE IF EXISTS `users`;