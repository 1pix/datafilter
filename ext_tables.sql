#
# Table structure for table 'tx_datafilter_filters'
#
CREATE TABLE tx_datafilter_filters (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	description text,
	configuration text,
	logical_operator varchar(3) DEFAULT 'AND' NOT NULL,
	orderby text,
	limit_start varchar(255) DEFAULT '' NOT NULL,
	limit_offset varchar(255) DEFAULT '' NOT NULL,
	limit_pointer varchar(255) DEFAULT '' NOT NULL,
	session_key  varchar(50) DEFAULT '' NOT NULL,
	key_per_page tinyint(4) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);
