CREATE TABLE fastd_media(
  id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(60) NOT NULL DEFAULT '',
  save_name VARCHAR(40) NOT NULL ,
  hash VARCHAR(40) NOT NULL,
  path VARCHAR(60) not NULL,
  size INT NOT NULL ,
  ext VARCHAR(10) NOT NULL ,
  create_at int(10) NOT NULL DEFAULT 0,
  update_at INT(10) NOT NULL DEFAULT 0
)engine=innodb charset=utf8;