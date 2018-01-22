<?php

namespace JudoIntranetMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use JudoIntranet\Legacy;

/**
 * migrate navi to orm_navi
 */
class Version20170331051047 extends AbstractMigration {
	
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
			CREATE TABLE orm_navi (
				id INT AUTO_INCREMENT NOT NULL,
				parent INT DEFAULT NULL,
				name VARCHAR(75) NOT NULL,
				file_param VARCHAR(75) DEFAULT NULL,
				url VARCHAR(75) DEFAULT NULL,
				position INT NOT NULL,
				`show` TINYINT(1) NOT NULL,
				valid TINYINT(1) NOT NULL,
				required_permission VARCHAR(1) DEFAULT \'r\' NOT NULL,
				icon VARCHAR(50) NOT NULL,
				last_modified DATETIME NOT NULL,
				INDEX IDX_C6D0CE493D8E604F (parent),
				PRIMARY KEY(id)
			)
			DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		
		$this->addSql('
			ALTER TABLE orm_navi
				ADD CONSTRAINT FK_C6D0CE493D8E604F FOREIGN KEY (parent) REFERENCES orm_navi (id)
		');
		
		// add manual SQL
		$this->addSql('
			INSERT INTO orm_navi
			SELECT `id`,NULL AS `parent`,`name`,`file_param`,NULL AS `url`,`position`,`show`,`valid`,`required_permission`,\'\' AS `icon`,`last_modified`
				FROM `navi`
				WHERE `parent` = 0
		');
		$this->addSql('
			INSERT INTO orm_navi
			SELECT `id`,`parent`,`name`,`file_param`,NULL AS `url`,`position`,`show`,`valid`,`required_permission`,\'\' AS `icon`,`last_modified`
				FROM `navi`
				WHERE `parent` <> 0
		');
		
		// update names
		foreach($this->getTranslation() as $old => $new) {
			
			$this->addSql('
					UPDATE `orm_navi` SET `name`=:new WHERE `name`=:old
				',
				array(
					'old' => $old,
					'new' => $new,
				),
				array('string', 'string')
			);
		}
		
		// update icons
		foreach($this->getIcons() as $id => $icon) {
			
			$this->addSql('
					UPDATE `orm_navi` SET `icon`=:icon WHERE `id`=:id
				',
					array(
							'id' => $id,
							'icon' => $icon,
					),
					array('string', 'string')
					);
		}
		
		// delete old table
		$this->addSql('
			DROP TABLE navi
		');
		
		// add max navi max depth to config
		$this->addSql('
			INSERT IGNORE INTO `config` (`name`, `value`, `comment`)
				VALUES (\'navi.maxDepth\', \'2\', \'The maximum depth that the navigation is generated\')
		');
	}
	
	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema) {
		
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
		
		// manual SQL
		$this->addSql('
			CREATE TABLE `navi` (
				`id` int(11) AUTO_INCREMENT NOT NULL,
				`name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
				`parent` int(11) NOT NULL,
				`file_param` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
				`position` int(3) NOT NULL,
				`show` tinyint(1) NOT NULL,
				`valid` tinyint(1) NOT NULL,
				`required_permission` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'r\',
				`last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY(id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		');
		
		$this->addSql('
			INSERT INTO navi
			SELECT `id`,`name`,\'0\' AS `parent`,`file_param`,`position`,`show`,`valid`,`required_permission`,`last_modified`
				FROM `orm_navi`
				WHERE `parent` IS NULL
		');
		$this->addSql('
			INSERT INTO navi
			SELECT `id`,`name`,`parent`,`file_param`,`position`,`show`,`valid`,`required_permission`,`last_modified`
				FROM `orm_navi`
				WHERE `parent` IS NOT NULL
		');
		
		// update names
		foreach($this->getTranslation() as $old => $new) {
			
			$this->addSql('
				UPDATE `navi` SET `name`=:old WHERE `name`=:new
			',
				array(
						'old' => $old,
						'new' => $new,
				),
				array('string', 'string')
			);
		}
		
		$this->addSql('
			DELETE FROM `config`
				WHERE `name`=\'navi.maxDepth\'
		');
		
		// add SQL from ORM
		$this->addSql('
			ALTER TABLE orm_navi DROP FOREIGN KEY FK_C6D0CE493D8E604F
		');
		
		$this->addSql('
			DROP TABLE orm_navi
		');
	}
	
	/**
	 * get translation
	 * 
	 * @return array
	 */
	private function getTranslation() {
		
		// prepare update navi translation
		return array(
			'navi: mainPage' => 'MainMenu.menu.homepageName',
			'navi: mainPage.login' => 'MainMenu.menu.homepage.login',
			'navi: mainPage.logout' => 'MainMenu.menu.homepage.logout',
			'navi: calendarPage' => 'MainMenu.menu.calendarName',
			'navi: calendarPage.new' => 'MainMenu.menu.calendar.new',
			'navi: calendarPage.listall' => 'MainMenu.menu.calendar.listall',
			'navi: calendarPage.details' => 'MainMenu.menu.calendar.details',
			'navi: calendarPage.edit' => 'MainMenu.menu.calendar.edit',
			'navi: calendarPage.delete' => 'MainMenu.menu.calendar.delete',
			'navi: calendarPage.calendar' => 'MainMenu.menu.calendar.calendar',
			'navi: calendarPage.schedule' => 'MainMenu.menu.calendar.schedule',
			'navi: inventoryPage' => 'MainMenu.menu.inventoryName',
			'navi: inventoryPage.my' => 'MainMenu.menu.inventory.my',
			'navi: inventoryPage.listall' => 'MainMenu.menu.inventory.listall',
			'navi: inventoryPage.give' => 'MainMenu.menu.inventory.give',
			'navi: inventoryPage.take' => 'MainMenu.menu.inventory.take',
			'navi: inventoryPage.cancel' => 'MainMenu.menu.inventory.cancel',
			'navi: inventoryPage.details' => 'MainMenu.menu.inventory.details',
			'navi: inventoryPage.movement' => 'MainMenu.menu.inventory.movement',
			'navi: announcementPage' => 'MainMenu.menu.announcementName',
			'navi: announcementPage.listall' => 'MainMenu.menu.announcement.listall',
			'navi: announcementPage.new' => 'MainMenu.menu.announcement.new',
			'navi: announcementPage.edit' => 'MainMenu.menu.announcement.edit',
			'navi: announcementPage.delete' => 'MainMenu.menu.announcement.delete',
			'navi: announcementPage.details' => 'MainMenu.menu.announcement.details',
			'navi: announcementPage.topdf' => 'MainMenu.menu.announcement.topdf',
			'navi: announcementPage.refreshpdf' => 'MainMenu.menu.announcement.refreshpdf',
			'navi: protocolPage' => 'MainMenu.menu.protocollName',
			'navi: protocolPage.listall' => 'MainMenu.menu.protocoll.listall',
			'navi: protocolPage.new' => 'MainMenu.menu.protocoll.new',
			'navi: protocolPage.details' => 'MainMenu.menu.protocoll.details',
			'navi: protocolPage.edit' => 'MainMenu.menu.protocoll.edit',
			'navi: protocolPage.show' => 'MainMenu.menu.protocoll.show',
			'navi: protocolPage.topdf' => 'MainMenu.menu.protocoll.topdf',
			'navi: protocolPage.delete' => 'MainMenu.menu.protocoll.delete',
			'navi: protocolPage.correct' => 'MainMenu.menu.protocoll.correct',
			'navi: protocolPage.showdecisions' => 'MainMenu.menu.protocoll.showdecisions',
			'navi: administrationPage' => 'MainMenu.menu.administrationName',
			'navi: administrationPage.field' => 'MainMenu.menu.administration.field',
			'navi: administrationPage.defaults' => 'MainMenu.menu.administration.defaults',
			'navi: administrationPage.useradmin' => 'MainMenu.menu.administration.useradmin',
			'navi: administrationPage.club' => 'MainMenu.menu.administration.club',
			'navi: administrationPage.newYear' => 'MainMenu.menu.administration.newYear',
			'navi: administrationPage.schoolholidays' => 'MainMenu.menu.administration.schoolholidays',
			'navi: filePage' => 'MainMenu.menu.fileName',
			'navi: filePage.listall' => 'MainMenu.menu.file.listall',
			'navi: filePage.details' => 'MainMenu.menu.file.details',
			'navi: filePage.edit' => 'MainMenu.menu.file.edit',
			'navi: filePage.delete' => 'MainMenu.menu.file.delete',
			'navi: filePage.upload' => 'MainMenu.menu.file.upload',
			'navi: filePage.cached' => 'MainMenu.menu.file.cached',
			'navi: filePage.attach' => 'MainMenu.menu.file.attach',
			'navi: filePage.download' => 'MainMenu.menu.file.download',
			'navi: filePage.logo' => 'MainMenu.menu.file.logo',
			'navi: resultPage' => 'MainMenu.menu.resultName',
			'navi: resultPage.listall' => 'MainMenu.menu.result.listall',
			'navi: resultPage.details' => 'MainMenu.menu.result.details',
			'navi: resultPage.delete' => 'MainMenu.menu.result.delete',
			'navi: resultPage.list' => 'MainMenu.menu.result.list',
			'navi: resultPage.new' => 'MainMenu.menu.result.new',
			'navi: resultPage.accounting' => 'MainMenu.menu.result.accounting',
			'navi: accountingPage' => 'MainMenu.menu.accountingName',
			'navi: accountingPage.dashboard' => 'MainMenu.menu.accounting.dashboard',
			'navi: accountingPage.task' => 'MainMenu.menu.accounting.task',
			'navi: accountingPage.settings' => 'MainMenu.menu.accounting.settings',
			'navi: tributePage' => 'MainMenu.menu.tributeName',
			'navi: tributePage.listall' => 'MainMenu.menu.tribute.listall',
			'navi: tributePage.new' => 'MainMenu.menu.tribute.new',
			'navi: tributePage.edit' => 'MainMenu.menu.tribute.edit',
			'navi: tributePage.delete' => 'MainMenu.menu.tribute.delete',
		);
	}
	
	/**
	 * get icons
	 *
	 * @return array
	 */
	private function getIcons() {
		
		// prepare icons
		return array(
			4 => 'calendar-o',
			6 => 'list',
			60 => 'calendar',
			63 => 'list-alt',
			10 => 'book',
			12 => 'list',
			11 => 'bookmark-o',
			24 => 'file-text-o',
			26 => 'list',
			33 => 'check-square-o',
			37 => 'file',
			38 => 'files-o',
			42 => 'upload',
			47 => 'flag-checkered',
			48 => 'calendar',
			53 => 'money',
			54 => 'dashboard',
			56 => 'cog',
			64 => 'gift',
			65 => 'list',
			34 => 'cogs',
			35 => 'table',
			45 => 'users',
			62 => 'calendar',
		);
	}
}
