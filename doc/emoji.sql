set names utf8;

/* 用户表 */
create table if not exists user
(
   id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
   name varchar(32) NOT NULL DEFAULT'' COMMENT '用户名称',
   password varchar(35) NOT NULL DEFAULT '' COMMENT '密码：md5格式',
   email varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱地址',
   phone char(11) NOT NULL DEFAULT '' COMMENT '手机号(仅支持大陆)',
   ip varchar(16) NOT NULL DEFAULT '' COMMENT '登录IP',
   app_id text NOT NULL DEFAULT '' COMMENT 'WX APP ID',
   open_id text NOT NULL DEFAULT '' COMMENT 'WX OPEN ID',
   remember_token varchar(100) DEFAULT NULL COMMENT '允许更新密码的时间戳',
   role tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 游客,1:普通用户,2: 风纪委员,3: admin.其余的为自定义',
   join_type tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 注册,1: activeCode加入',
   custom_account tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 未更改,1: 更改完毕',
   created_at timestamp NULL DEFAULT NULL,
   updated_at timestamp NULL DEFAULT NULL,
   last_login timestamp NULL DEFAULT NULL
);

/* 角色表 */
create table if not exists role
(
   id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
   name varchar(32) NOT NULL DEFAULT '' COMMENT '角色名称',
   menu text NOT NULL DEFAULT '' COMMENT 'menu',
   power text NOT NULL DEFAULT '' COMMENT '角色权限',
   role_desc varchar(64) NOT NULL DEFAULT '' COMMENT '角色描述',
   created_at timestamp NULL DEFAULT NULL,
   updated_at timestamp NULL DEFAULT NULL
);

/* 菜单表 */
create table if not exists menu
(
   id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
   name varchar(20) NOT NULL DEFAULT '' COMMENT '菜单名称',
   parent int unsigned NOT NULL DEFAULT 0 COMMENT '父级ID',
   id_level tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '菜单级别',
   menu_desc varchar(64) NOT NULL DEFAULT '' COMMENT '菜单描述',
   icon text NOT NULL DEFAULT '' COMMENT 'icon（预留）',
   href text NOT NULL DEFAULT '' COMMENT 'href（预留）'
);

/* action表 */
create table if not exists action
(
   id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
   name varchar(20) NOT NULL DEFAULT '' COMMENT 'action名称',
   action varchar(64) NOT NULL DEFAULT '' COMMENT '对应method名称',
   parent int unsigned NOT NULL DEFAULT 0 COMMENT '父级ID'
);

/* 激活码表 */
create table if not exists activeCode
(
	id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
	code text NOT NULL DEFAULT '' COMMENT '激活码',
	user_id int unsigned DEFAULT 0 NOT NULL COMMENT '用户ID',
	enable tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否有效:0. 无效,1. 有效',
	useable tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否激活:0. 未激活,1. 激活',
	generate_type tinyint(1) NOT NULL DEFAULT 0 COMMENT '激活码生成方式:1. crontab,2. 手动生成',
	created_at timestamp NULL DEFAULT NULL,
    used_at timestamp NULL DEFAULT NULL
);

/* 小黑屋名单表 */
create table if not exists blackHouse 
(
	id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
	user_id int unsigned DEFAULT 0 NOT NULL COMMENT '用户ID',
	user_ip varchar(16) NOT NULL DEFAULT '' COMMENT '禁止登录的IP'
);

/* system 设置表 */
create table if not exists systemConfig 
(
	id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name varchar(32) NOT NULL DEFAULT '' COMMENT 'key',
	value text NOT NULL DEFAULT '' COMMENT 'value',
	user_id int unsigned DEFAULT 0 NOT NULL COMMENT '用户ID',
	enable tinyint(1) unsigned DEFAULT 1 COMMENT '是否启用:0. 不生效,1: 生效',
	opened_at timestamp NULL DEFAULT NULL,
	closed_at timestamp NULL DEFAULT NULL
);
