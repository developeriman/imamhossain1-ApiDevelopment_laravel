CREATE TABLE `tbl_users` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`first_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`last_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`username` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`usertype` INT NOT NULL,
	`status` INT NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);


CREATE TABLE `tbl_user_login` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`user_id` INT NOT NULL,
	`auth_token` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_forget_password` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`token` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_project` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`project_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`project_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
	`start_date` TIMESTAMP NULL DEFAULT NULL ,
	`target_end_date` TIMESTAMP NULL DEFAULT NULL ,
	`end_date` TIMESTAMP NULL DEFAULT NULL ,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_project_to_user` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`project_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);


CREATE TABLE `tbl_task` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`task_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`task_details` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_task_to_project` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`task_id` INT NOT NULL,
	`project_id` INT NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_task_to_user` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`task_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_task_status` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`status_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_task_to_status` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`task_id` INT NOT NULL,
	`status_id` INT NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_task_time` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`task_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`time_type` INT NOT NULL,
	`time` TIMESTAMP NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_task_files` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`task_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`file` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_task_comments` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`task_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`comment` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`file` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);

CREATE TABLE `tbl_user_working_record` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`user_id` INT NOT NULL,
	`time_type` INT NOT NULL,
	`start_time` INT NOT NULL,
	`end_time` TIMESTAMP NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);


CREATE TABLE `tbl_user_working_screenshot`(
	`id` INT NOT NULL AUTO_INCREMENT,
	`user_id` INT NOT NULL,
	`screenshot` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL ,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ,
	PRIMARY KEY (`id`)
);