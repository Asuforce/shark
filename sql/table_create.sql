create table user(
    user_id int not null auto_increment primary key,
    user_name varchar(16) not null unique,
    password char(64) not null,
    name varchar(16) not null,
    mail_address varchar(256) not null,
    created datetime default null
);

create table conversation(
    conversation_id int not null,
    user_id int not null,
    count int not null
);

create table message(
    message_id int not null auto_increment primary key,
    conversation_id int not null,
    user_id int not null,
    body varchar(200) not null,
    is_read int(1) not null,
    created datetime default null
);

create table profile(
    user_id int not null,
    sex int(1) default null,
    introduction varchar(100) default null,
    pro_image varchar(256) not null
);

create table follow(
    user_id int not null,
    follow_id int not null,
    follow_date datetime default null
);

create table information(
     user_id int not null,
     other_id int not null,
     read_check int(1) not null,
     info_status int(2) not null,
     info_date datetime default null
);

create table material(
    material_id int not null auto_increment primary key,
    user_id int not null,
    instrument_id int not null,
    submit_date datetime default null,
    material_comment varchar(100) default null,
    material_path varchar(256) default null
);

create table record_data(
    record_id int not null auto_increment primary key,
    user_id int not null,
    record_name varchar(50) not null,
    record_date datetime default null,
    record_comment varchar(100) default null
);

create table record(
    record_id int not null,
    material_id int not null
);

create table fav_material(
    user_id int not null,
    material_id int not null,
    fav_mate_date datetime default null
);

create table fav_record(
    user_id int not null,
    record_id int not null,
    fav_reco_date datetime default null
);

create table instrument (
    instrument_id int not null,
    instrument_name varchar(15) not null,
    instrument_image varchar(256) default null
);
