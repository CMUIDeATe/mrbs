-- $Id: tables.pg.sql 1150 2009-07-08 12:04:50Z cimorrison $
--
-- MRBS table creation script - for PostgreSQL
--
-- Notes:
-- MySQL inserts the current date/time into any timestamp field which is not
-- specified on insert. To get the same effect, use PostgreSQL default
-- value current_timestamp.
--
-- If you have decided to change the prefix of your tables from 'mrbs_'
-- to something else using $db_tbl_prefix then you must edit each
-- 'CREATE TABLE', 'create index' and 'INSERT INTO' line below to replace
-- 'mrbs_' with your new table prefix.
--
-- If you change the varchar lengths here, then you should check
-- to see whether a corresponding length has been defined in the config file
-- in the array $maxlength.

CREATE TABLE mrbs_area
(
  id                    serial primary key,
  area_name             varchar(30),
  area_admin_email      text,
  resolution            int,
  default_duration      int,
  morningstarts         int,
  morningstarts_minutes int,
  eveningends           int,
  eveningends_minutes   int,
  private_enabled       smallint,
  private_default       smallint,
  private_mandatory     smallint,
  private_override      varchar(32)
);

CREATE TABLE mrbs_room
(
  id                serial primary key,
  area_id           int DEFAULT 0 NOT NULL,
  room_name         varchar(25) DEFAULT '' NOT NULL,
  description       varchar(60),
  capacity          int DEFAULT 0 NOT NULL,
  room_admin_email  text
);

CREATE TABLE mrbs_entry
(
  id          serial primary key,
  start_time  int DEFAULT 0 NOT NULL,
  end_time    int DEFAULT 0 NOT NULL,
  entry_type  int DEFAULT 0 NOT NULL,
  repeat_id   int DEFAULT 0 NOT NULL,
  room_id     int DEFAULT 1 NOT NULL,
  timestamp   timestamp DEFAULT current_timestamp,
  create_by   varchar(80) DEFAULT '' NOT NULL,
  name        varchar(80) DEFAULT '' NOT NULL,
  type        char DEFAULT 'E' NOT NULL,
  description text,
  private     smallint DEFAULT 0 NOT NULL
);
create index idxStartTime on mrbs_entry(start_time);
create index idxEndTime on mrbs_entry(end_time);

CREATE TABLE mrbs_repeat
(
  id          serial primary key,
  start_time  int DEFAULT 0 NOT NULL,
  end_time    int DEFAULT 0 NOT NULL,
  rep_type    int DEFAULT 0 NOT NULL,
  end_date    int DEFAULT 0 NOT NULL,
  rep_opt     varchar(32) DEFAULT '' NOT NULL,
  room_id     int DEFAULT 1 NOT NULL,
  timestamp   timestamp DEFAULT current_timestamp,
  create_by   varchar(80) DEFAULT '' NOT NULL,
  name        varchar(80) DEFAULT '' NOT NULL,
  type        char DEFAULT 'E' NOT NULL,
  description text,
  rep_num_weeks smallint DEFAULT NULL NULL,
  private     smallint DEFAULT 0 NOT NULL
);

CREATE TABLE mrbs_variables
(
  id               serial primary key,
  variable_name    varchar(80),
  variable_content text
);

CREATE TABLE mrbs_users
(
  /* The first four fields are required. Don't remove. */
  id        serial primary key,
  level     smallint DEFAULT '0' NOT NULL,  /* play safe and give no rights */
  name      varchar(30),
  password  varchar(40),
  email     varchar(75),

  PRIMARY KEY (id)
);

INSERT INTO mrbs_variables (variable_name, variable_content)
  VALUES ('db_version', '6');
INSERT INTO mrbs_variables (variable_name, variable_content)
  VALUES ('local_db_version', '1');
