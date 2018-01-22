<?php

namespace JudoIntranetMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use JudoIntranet\Legacy;

/**
 * migrate config to orm_config
 */
class Version20170410061612 extends AbstractMigration {
	
	/**
	 * @param Schema $Schema
	 */
	public function preUp(Schema $schema) {
		
		// check version and abort if below 2.0.0
		$this->abortIf(Legacy::isMigrationUsable($this->connection) === false, '"global.version" is below 2.0.0, please update to 2.0.0 first using webbased setup!');
	}
	
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema) {
		
		// add SQL from ORM
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
		
		$this->addSql('
			CREATE TABLE orm_config (
				name VARCHAR(50) NOT NULL,
				modified_by INT DEFAULT NULL,
				value LONGTEXT NOT NULL,
				comment VARCHAR(100) NOT NULL,
				valid TINYINT(1) NOT NULL,
				last_modified DATETIME NOT NULL,
				INDEX IDX_E6087E3325F94802 (modified_by),
				PRIMARY KEY(name)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		
		$this->addSql('
			ALTER TABLE orm_config
				ADD CONSTRAINT FK_E6087E3325F94802 FOREIGN KEY (modified_by) REFERENCES user (id)
		');
		
		// add manual SQL
		$this->addSql('
			INSERT INTO orm_config
			SELECT `name`,1 AS `modified_by`,`value`,`comment`,TRUE AS `valid`,CURRENT_TIMESTAMP AS `last_modified`
				FROM `config`
		');
		
		// delete old table
		$this->addSql('
			DROP TABLE config
		');
		
		// remove global.version entry
		$this->addSql('
			DELETE FROM orm_config
				WHERE name=\'global.version\'
		');
	}
	
	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema) {
		
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
		
		// manual SQL
		$this->addSql('
			CREATE TABLE `config` (
				`name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				`value` text COLLATE utf8_unicode_ci NOT NULL,
				`comment` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
				PRIMARY KEY(name)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		');
		
		$this->addSql('
			INSERT INTO config
			SELECT `name`,`value`,`comment`
				FROM `orm_config`
		');
		
		// reset global.version
		$this->addSql('
			INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
			VALUES (\'global.version\', \'2.0.0\', \'\')
		');
		
		// add SQL from ORM
		$this->addSql('
			DROP TABLE orm_config
		');
	}
}
