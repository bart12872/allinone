CREATE TABLE  `allinone`.`binsearch_match` (
`binsearch_collection_id` INT NOT NULL ,
`binsearch_regex_id` INT NOT NULL ,
`name` VARCHAR( 256 ) NOT NULL ,
`season` VARCHAR( 8 ) NOT NULL ,
`episode` VARCHAR( 8 ) NOT NULL ,
`cancel` BOOLEAN NOT NULL DEFAULT  '0',
`inserted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (  `binsearch_collection_id` ,  `binsearch_regex_id` )
) ENGINE = INNODB;

ALTER TABLE  `binsearch_match` ADD FOREIGN KEY (  `binsearch_collection_id` ) REFERENCES  `allinone`.`binsearch_collection` (
`binsearch_collection_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `binsearch_match` ADD FOREIGN KEY (  `binsearch_regex_id` ) REFERENCES  `allinone`.`binsearch_regex` (
`binsearch_regex_id`
) ON DELETE CASCADE ON UPDATE CASCADE ;

ALTER TABLE  `binsearch_regex` CHANGE  `update`  `updated` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE  `binsearch_regex` ADD  `active` BOOLEAN NOT NULL DEFAULT  '1' AFTER  `min_size`;
ALTER TABLE  `binsearch_collection` CHANGE  `insert`  `inserted` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;