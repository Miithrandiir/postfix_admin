/**
  VERSION FOR MARIADB / MYSQL
 */
create table postfix_backup_server
(
    id         int auto_increment
        primary key,
    name       varchar(64)          not null,
    adresse_ip varchar(64)          not null,
    username   varchar(64)          not null,
    password   varchar(255)         not null,
    active     tinyint(1) default 0 not null,
    constraint postfix_backup_server_adresse_ip_uindex
        unique (adresse_ip),
    constraint postfix_backup_server_name_uindex
        unique (name),
    constraint postfix_backup_server_password_uindex
        unique (password),
    constraint postfix_backup_server_username_uindex
        unique (username)
)
    charset = latin1;

create table user
(
    id       int auto_increment
        primary key,
    username varchar(180)                 not null,
    roles    longtext collate utf8mb4_bin not null,
    password varchar(255)                 not null,
    constraint UNIQ_8D93D649F85E0677
        unique (username)
)
    collate = utf8mb4_unicode_ci;

create table postfix_domain
(
    id            int auto_increment
        primary key,
    domain        varchar(255)                         not null,
    user_id       int                                  null,
    description   text                                 null,
    nb_aliases    int        default 0                 not null,
    nb_mailboxes  int        default 0                 not null,
    maxquota      bigint     default 0                 not null,
    quota         bigint     default 0                 not null,
    backupmx      tinyint(1) default 0                 not null,
    date_created  datetime   default CURRENT_TIMESTAMP not null,
    date_modified datetime   default CURRENT_TIMESTAMP not null,
    active        tinyint(1) default 0                 not null,
    constraint postfix_domain_domain_uindex
        unique (domain),
    constraint postfix_domain_user_id_fk
        foreign key (user_id) references user (id)
)
    comment 'Postfix Admin - Virtual Domains' charset = latin1;

create table postfix_alias
(
    id            int auto_increment
        primary key,
    domain_id     int                                  not null,
    adress        varchar(64)                          not null,
    goto          text                                 not null,
    date_created  datetime   default CURRENT_TIMESTAMP not null,
    date_modified datetime   default CURRENT_TIMESTAMP not null,
    active        tinyint(1) default 0                 not null,
    constraint postfix_alias_domain_id_adress_uindex
        unique (domain_id, adress),
    constraint postfix_alias_postfix_domain_id_fk
        foreign key (domain_id) references postfix_domain (id)
)
    charset = latin1;

create table postfix_alias_domain
(
    id               int auto_increment
        primary key,
    domain_origin_id int                                  not null,
    domain_target_id int                                  not null,
    date_created     datetime   default CURRENT_TIMESTAMP not null,
    date_modified    datetime   default CURRENT_TIMESTAMP not null,
    active           tinyint(1) default 0                 not null,
    constraint postfix_alias_domain_postfix_domain_id_fk
        foreign key (domain_target_id) references postfix_domain (id),
    constraint postfix_alias_domain_postfix_domain_id_fk_2
        foreign key (domain_origin_id) references postfix_domain (id)
)
    charset = latin1;

create index postfix_alias_domain_domain_origin_id_domain_target_id_index
    on postfix_alias_domain (domain_origin_id, domain_target_id);

create table postfix_backup_server_domain
(
    postfix_domain_id        int not null,
    postfix_backup_server_id int not null,
    primary key (postfix_domain_id, postfix_backup_server_id),
    constraint postfix_backup_server_domain_postfix_backup_server_id_fk
        foreign key (postfix_backup_server_id) references postfix_backup_server (id),
    constraint postfix_backup_server_domain_postfix_domain_id_fk
        foreign key (postfix_domain_id) references postfix_domain (id)
)
    charset = latin1;

create table postfix_mailbox
(
    id            int auto_increment
        primary key,
    domain_id     int                                  not null,
    username      varchar(255)                         not null,
    password      varchar(255)                         not null,
    name          varchar(64)                          null,
    firstname     varchar(64)                          null,
    maildir       varchar(100)                         null,
    quota         bigint     default 0                 not null,
    date_created  datetime   default CURRENT_TIMESTAMP not null,
    date_modified datetime   default CURRENT_TIMESTAMP not null,
    active        tinyint(1) default 0                 not null,
    constraint postfix_mailbox_domain_id_username_uindex
        unique (domain_id, username),
    constraint postfix_mailbox_postfix_domain_id_fk
        foreign key (domain_id) references postfix_domain (id)
)
    charset = latin1;

create table user_token
(
    id         int auto_increment
        primary key,
    id_user_id int                                              not null,
    token      varchar(250)                                     not null,
    max_date   datetime                                         not null,
    CreateDate datetime(6) default '0001-01-01 00:00:00.000000' not null,
    constraint UNIQ_BDF55A635F37A13B
        unique (token),
    constraint FK_BDF55A6379F37AE5
        foreign key (id_user_id) references user (id)
)
    collate = utf8mb4_unicode_ci;

create index IDX_BDF55A6379F37AE5
    on user_token (id_user_id);

