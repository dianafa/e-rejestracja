CREATE TABLE `erej_roles` (
	`id` INTEGER NOT NULL PRIMARY KEY,
	`name` TEXT NOT NULL,
	`description` TEXT NOT NULL
);

CREATE TABLE `erej_users` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`role_id` INTEGER NOT NULL REFERENCES `erej_roles`(`id`),
	`PESEL` INTEGER NOT NULL,
	`password` TEXT NOT NULL,
	`name` TEXT NOT NULL,
	`surname` TEXT NOT NULL
);

CREATE TABLE `erej_sexes` (
	`id` INTEGER NOT NULL PRIMARY KEY,
	`name` TEXT NOT NULL
);

CREATE TABLE erej_patients (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	user_id INTEGER NOT NULL REFERENCES erej_users(id),
	sex_id INTEGER NOT NULL REFERENCES erej_sexes(id),
	NIP INTEGER,
	address TEXT NOT NULL,
	birthdate TEXT NOT NULL,
	birthplace TEXT NOT NULL,
	idcard TEXT NOT NULL,
	phone TEXT NOT NULL,
	email TEXT NOT NULL,
	emergencyPhone TEXT NOT NULL
);

CREATE TABLE `erej_receptionists` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`user_id` INTEGER NOT NULL REFERENCES `erej_users`(`id`)
);

CREATE TABLE `erej_doctors` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`user_id` INTEGER NOT NULL REFERENCES `erej_users`(`id`)
);

CREATE TABLE `erej_specialitys` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name` TEXT NOT NULL
);

CREATE TABLE `erej_doctors_specialitys` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`doctor_id` INTEGER NOT NULL REFERENCES `erej_doctors`(`id`),
	`speciality_id` INTEGER NOT NULL REFERENCES `erej_specialitys`(`id`)
);

CREATE TABLE `erej_registrations` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`patient_id` INTEGER NOT NULL REFERENCES `erej_patients`(`id`),
	`time` TEXT NOT NULL,
	`doctor_id` INTEGER NOT NULL REFERENCES `erej_doctors`(`id`),
	`speciality_id` INTEGER NOT NULL REFERENCES `erej_specialitys`(`id`)
);

CREATE TABLE `erej_reasons` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name` TEXT NOT NULL
);

CREATE TABLE `erej_visits` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`patient_id` INTEGER NOT NULL REFERENCES `erej_patients`(`id`),
	`time` TEXT NOT NULL,
	`reason_id` INTEGER REFERENCES `erej_reasons`(`id`),
	`reason` TEXT,
	`note` TEXT,
	`diagnoses` TEXT, --zmien w tabeli na diagnosEs
	`procedures` TEXT
);

CREATE TABLE `erej_medicines` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        `code` INTEGER NOT NULL,
	`name` TEXT NOT NULL
) ;

CREATE TABLE `erej_diagnoses` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        `code` TEXT NOT NULL UNIQUE  DEFAULT A,,
	`name` TEXT NOT NULL
) ;

CREATE  TABLE "main"."erej_procedures" (
        "id" INTEGER PRIMARY KEY  NOT NULL  UNIQUE , 
        "code" TEXT, 
        "name" TEXT,
) ;

CREATE TABLE `erej_medicines_visits` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`medicine_id` INTEGER NOT NULL REFERENCES `erej_medicines`(`id`),
	`visit_id` INTEGER NOT NULL REFERENCES `erej_visits`(`id`)
);

CREATE TABLE `erej_reminders` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`visit_id` INTEGER NOT NULL REFERENCES `erej_visits`(`id`),
	`time` TEXT NOT NULL,
	`sent` INTEGER NOT NULL DEFAULT 0
);