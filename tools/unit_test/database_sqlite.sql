/**
  VERSION FOR SQLITE
 */

DROP TABLE IF EXISTS postfix_backup_server;
create table postfix_backup_server
(
    id         INTEGER primary key autoincrement,
    name       varchar(64)  not null,
    adresse_ip varchar(64)  not null,
    username   varchar(64)  not null,
    password   varchar(255) not null,
    active     tinyint(1) default 0 not null,
    constraint postfix_backup_server_adresse_ip_uindex
        unique (adresse_ip),
    constraint postfix_backup_server_name_uindex
        unique (name),
    constraint postfix_backup_server_password_uindex
        unique (password),
    constraint postfix_backup_server_username_uindex
        unique (username)
);
DROP TABLE IF EXISTS user;
create table user
(
    id       INTEGER primary key autoincrement,
    username varchar(180) not null,
    roles    TEXT         not null,
    password varchar(255) not null,
    constraint UNIQ_8D93D649F85E0677
        unique (username)
);
DROP TABLE IF EXISTS postfix_domain;
create table postfix_domain
(
    id            INTEGER primary key autoincrement,
    domain        varchar(255) not null,
    user_id       INTEGER      null,
    description   text         null,
    nb_aliases    INTEGER    default 0 not null,
    nb_mailboxes  INTEGER    default 0 not null,
    maxquota      INTEGER    default 0 not null,
    quota         INTEGER    default 0 not null,
    backupmx      tinyint(1) default 0 not null,
    date_created  datetime   default CURRENT_TIMESTAMP not null,
    date_modified datetime   default CURRENT_TIMESTAMP not null,
    active        tinyint(1) default 0 not null,
    constraint postfix_domain_domain_uindex
        unique (domain),
    constraint postfix_domain_user_id_fk
        foreign key (user_id) references user (id)
);
DROP TABLE IF EXISTS postfix_alias;
create table postfix_alias
(
    id            INTEGER primary key autoincrement,
    domain_id     INTEGER     not null,
    adress        varchar(64) not null,
    goto          text        not null,
    date_created  datetime   default CURRENT_TIMESTAMP not null,
    date_modified datetime   default CURRENT_TIMESTAMP not null,
    active        tinyint(1) default 0 not null,
    constraint postfix_alias_domain_id_adress_uindex
        unique (domain_id, adress),
    constraint postfix_alias_postfix_domain_id_fk
        foreign key (domain_id) references postfix_domain (id)
);
DROP TABLE IF EXISTS postfix_alias_domain;
create table postfix_alias_domain
(
    id               INTEGER primary key autoincrement,
    domain_origin_id INTEGER not null,
    domain_target_id INTEGER not null,
    date_created     datetime   default CURRENT_TIMESTAMP not null,
    date_modified    datetime   default CURRENT_TIMESTAMP not null,
    active           tinyint(1) default 0 not null,
    constraint postfix_alias_domain_postfix_domain_id_fk
        foreign key (domain_target_id) references postfix_domain (id),
    constraint postfix_alias_domain_postfix_domain_id_fk_2
        foreign key (domain_origin_id) references postfix_domain (id)
);

create index postfix_alias_domain_domain_origin_id_domain_target_id_index
    on postfix_alias_domain (domain_origin_id, domain_target_id);
DROP TABLE IF EXISTS postfix_backup_server_domain;
create table postfix_backup_server_domain
(
    postfix_domain_id        INTEGER not null,
    postfix_backup_server_id INTEGER not null,
    primary key (postfix_domain_id, postfix_backup_server_id),
    constraint postfix_backup_server_domain_postfix_backup_server_id_fk
        foreign key (postfix_backup_server_id) references postfix_backup_server (id),
    constraint postfix_backup_server_domain_postfix_domain_id_fk
        foreign key (postfix_domain_id) references postfix_domain (id)
);
DROP TABLE IF EXISTS postfix_mailbox;
create table postfix_mailbox
(
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    domain_id     INTEGER      not null,
    username      varchar(255) not null,
    password      varchar(255) not null,
    name          varchar(64)  null,
    firstname     varchar(64)  null,
    maildir       varchar(100) null,
    quota         INTEGER    default 0 not null,
    date_created  datetime   default CURRENT_TIMESTAMP not null,
    date_modified datetime   default CURRENT_TIMESTAMP not null,
    active        tinyint(1) default 0 not null,
    constraint postfix_mailbox_domain_id_username_uindex
        unique (domain_id, username),
    constraint postfix_mailbox_postfix_domain_id_fk
        foreign key (domain_id) references postfix_domain (id)
);
DROP TABLE IF EXISTS user_token;
create table user_token
(
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    id_user_id int          not null,
    token      varchar(250) not null,
    max_date   datetime     not null,
    CreateDate datetime(6) default '0001-01-01 00:00:00.000000' not null,
    constraint UNIQ_BDF55A635F37A13B
        unique (token),
    constraint FK_BDF55A6379F37AE5
        foreign key (id_user_id) references user (id)
);

create index IDX_BDF55A6379F37AE5
    on user_token (id_user_id);