<?php

namespace JudoIntranetMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use JudoIntranet\Legacy;

/**
 * add Logo and FileType
 */
class Version20170310174256 extends AbstractMigration {

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
		
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
		
		// add SQL from ORM
		$this->addSql('
			CREATE TABLE orm_filetype (
				id INT AUTO_INCREMENT NOT NULL,
				modified_by INT DEFAULT NULL,
				name VARCHAR(150) NOT NULL,
				mime_type VARCHAR(100) NOT NULL,
				extension VARCHAR(10) NOT NULL,
				valid TINYINT(1) NOT NULL,
				last_modified DATETIME NOT NULL,
				INDEX IDX_857BC06025F94802 (modified_by),
				PRIMARY KEY(id)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$this->addSql('
			CREATE TABLE orm_logo (
				id INT AUTO_INCREMENT NOT NULL,
				modified_by INT DEFAULT NULL,
				filetype INT DEFAULT NULL,
				name VARCHAR(150) NOT NULL,
				data LONGBLOB NOT NULL,
				valid TINYINT(1) NOT NULL,
				last_modified DATETIME NOT NULL,
				INDEX IDX_DCFDADED25F94802 (modified_by),
				INDEX IDX_DCFDADEDEEF6C04A (filetype),
				PRIMARY KEY(id)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$this->addSql('
			ALTER TABLE orm_filetype
				ADD CONSTRAINT FK_857BC06025F94802 FOREIGN KEY (modified_by) REFERENCES user (id)
		');
		$this->addSql('
			ALTER TABLE orm_logo
				ADD CONSTRAINT FK_DCFDADED25F94802 FOREIGN KEY (modified_by) REFERENCES user (id)
		');
		$this->addSql('
			ALTER TABLE orm_logo
				ADD CONSTRAINT FK_DCFDADEDEEF6C04A FOREIGN KEY (filetype) REFERENCES orm_filetype (id)
		');
		
		// add manual SQL
		$this->addSql(
			'UPDATE `config` SET `value`=:value WHERE `name`=:name',
			array(
				'value' => 'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter,file,file_type,files_attached,club,result,standings,accounting_tasks,accounting_costs,holiday,tribute,tribute_history,accounting_settings,tribute_file,webservice_jobs,webservice_results,orm_filetype,orm_logo',
				'name' => 'systemtables',),
			array('string', 'string')
		);
	}

	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema) {
		
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
		
		// add SQL from ORM
		$this->addSql('
			ALTER TABLE orm_logo
				DROP FOREIGN KEY FK_DCFDADEDEEF6C04A
		');
		$this->addSql('
			DROP TABLE orm_filetype
		');
		$this->addSql('
			DROP TABLE orm_logo
		');
		
		// add manual SQL
		$this->addSql(
			'UPDATE `config` SET `value`=:value WHERE `name`=:name',
			array(
				'value' => 'calendar,category,config,defaults,field,fields2presets,group,group2group,inventory,inventory_movement,preset,rights,user,user2group,value,protocol,protocol_correction,helpmessages,user2groups,permissions,navi,item2filter,groups,filter,file,file_type,files_attached,club,result,standings,accounting_tasks,accounting_costs,holiday,tribute,tribute_history,accounting_settings,tribute_file,webservice_jobs,webservice_results',
				'name' => 'systemtables',),
			array('string', 'string')
		);
	}
}
