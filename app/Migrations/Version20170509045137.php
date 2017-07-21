<?php

namespace JudoIntranetMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use JudoIntranet\Legacy;
use JudoIntranet\Migrate\DbMigrateSecurity;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use AppBundle\Entity\Role;

/**
 * migrate to fos user bundle and sonatra security
 */
class Version20170509045137 extends AbstractMigration implements ContainerAwareInterface {
	
	use ContainerAwareTrait;
	
	/**
	 * @param Schema $Schema
	 */
	public function preUp(Schema $schema) {
		
		// check version and abort if below 2.0.0
		$this->abortIf(Legacy::isMigrationUsable($this->connection) === false, '"global.version" is below 2.0.0, please update to 2.0.0 first using webbased setup!');
		
		// warning password
		$this->warnIf(true, 
			'This migration changes the encryption and hashing of the user passwords, '.
			'migrating down will reset previous structure, but clear all passwords to empty string!
		');
	}
	
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema) {
		
		// add SQL from ORM
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
		
		// get migration object
		$jiMigration = new DbMigrateSecurity($this->connection);
		
		
		$this->addSql('
			CREATE TABLE fos_group (
				id INT AUTO_INCREMENT NOT NULL,
				name VARCHAR(180) NOT NULL,
				roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
				valid TINYINT(1) NOT NULL,
				last_modified DATETIME NOT NULL,
				UNIQUE INDEX UNIQ_4B019DDB5E237E06 (name),
				PRIMARY KEY(id)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$this->addSql('
			CREATE TABLE fos_group_groups (
				parent_id INT NOT NULL,
				child_id INT NOT NULL,
				INDEX IDX_70177A5D727ACA70 (parent_id),
				INDEX IDX_70177A5DDD62C21B (child_id),
				PRIMARY KEY(parent_id, child_id)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$result = $jiMigration->migrateGroupsUp();
		$this->abortIf(!$result['retval'], implode(PHP_EOL, $result['result']));
		$this->addSql($result['result']);
		$this->addSql('
			DROP TABLE `groups`
		');
		$this->addSql('
			CREATE TABLE son_permission (
				id INT AUTO_INCREMENT NOT NULL,
				operation VARCHAR(255) NOT NULL,
				contexts LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
				class VARCHAR(255) DEFAULT NULL,
				field VARCHAR(255) DEFAULT NULL,
				last_modified DATETIME NOT NULL,
				INDEX operation_idx (operation),
				INDEX class_idx (class),
				INDEX field_idx (field),
				UNIQUE INDEX unique_permission_idx (operation, class, field),
				PRIMARY KEY(id)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$this->addSql('
			CREATE TABLE son_role (
				id INT AUTO_INCREMENT NOT NULL,
				name VARCHAR(255) NOT NULL,
				valid TINYINT(1) NOT NULL,
				last_modified DATETIME NOT NULL,
				UNIQUE INDEX UNIQ_C49316505E237E06 (name),
				PRIMARY KEY(id)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$this->addSql('
			CREATE TABLE son_role_roles (
				parent_id INT NOT NULL,
				child_id INT NOT NULL,
				INDEX IDX_E67BD858727ACA70 (parent_id),
				INDEX IDX_E67BD858DD62C21B (child_id),
				PRIMARY KEY(parent_id, child_id)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$this->addSql('
			CREATE TABLE son_role_permission (
				role_id INT NOT NULL,
				permission_id INT NOT NULL,
				INDEX IDX_DCF8796FD60322AC (role_id),
				INDEX IDX_DCF8796FFED90CCA (permission_id),
				PRIMARY KEY(role_id, permission_id)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$this->addSql('
			CREATE TABLE son_sharing (
				id INT AUTO_INCREMENT NOT NULL,
				subject_class VARCHAR(244) NOT NULL,
				subject_id VARCHAR(36) NOT NULL,
				identity_class VARCHAR(244) NOT NULL,
				identity_name VARCHAR(244) NOT NULL,
				enabled TINYINT(1) NOT NULL,
				roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
				started_at DATETIME DEFAULT NULL,
				ended_at DATETIME DEFAULT NULL,
				last_modified DATETIME NOT NULL,
				identity_id INT NOT NULL,
				INDEX subject_class_idx (subject_class),
				INDEX subject_id_idx (subject_id),
				INDEX identity_class_idx (identity_class),
				INDEX identity_name_idx (identity_name),
				UNIQUE INDEX unique_sharing_idx (subject_class, subject_id, identity_class, identity_name),
				PRIMARY KEY(id)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$this->addSql('
			CREATE TABLE son_sharing_permissions (
				sharing_id INT NOT NULL,
				permission_id INT NOT NULL,
				INDEX IDX_A9C9F7B48F15050 (sharing_id),
				INDEX IDX_A9C9F7BFED90CCA (permission_id),
				PRIMARY KEY(sharing_id, permission_id)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$this->addSql('
			CREATE TABLE fos_user (
				id INT AUTO_INCREMENT NOT NULL,
				username VARCHAR(180) NOT NULL,
				username_canonical VARCHAR(180) NOT NULL,
				email VARCHAR(180) NOT NULL,
				email_canonical VARCHAR(180) NOT NULL,
				enabled TINYINT(1) NOT NULL,
				salt VARCHAR(255) DEFAULT NULL,
				password VARCHAR(255) NOT NULL,
				last_login DATETIME DEFAULT NULL,
				confirmation_token VARCHAR(180) DEFAULT NULL,
				password_requested_at DATETIME DEFAULT NULL,
				roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
				name VARCHAR(50) NOT NULL,
				last_modified DATETIME NOT NULL,
				UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical),
				UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical),
				UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token),
				PRIMARY KEY(id)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$this->addSql('
			CREATE TABLE fos_user_groups (
				user_id INT NOT NULL,
				group_id INT NOT NULL,
				INDEX IDX_DA37EFBFA76ED395 (user_id),
				INDEX IDX_DA37EFBFFE54D947 (group_id),
				PRIMARY KEY(user_id, group_id)
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
		');
		$result = $jiMigration->migrateUsersUp();
		$this->abortIf(!$result['retval'], implode(PHP_EOL, $result['result']));
		$this->addSql($result['result']);
		$this->addSql('
			ALTER TABLE fos_group_groups
				ADD CONSTRAINT FK_70177A5D727ACA70 FOREIGN KEY (parent_id) REFERENCES fos_group (id)
		');
		$this->addSql('
			ALTER TABLE fos_group_groups
				ADD CONSTRAINT FK_70177A5DDD62C21B FOREIGN KEY (child_id) REFERENCES fos_group (id)
		');
		$this->addSql('
			ALTER TABLE son_role_roles
				ADD CONSTRAINT FK_E67BD858727ACA70 FOREIGN KEY (parent_id) REFERENCES son_role (id)
		');
		$this->addSql('
			ALTER TABLE son_role_roles
				ADD CONSTRAINT FK_E67BD858DD62C21B FOREIGN KEY (child_id) REFERENCES son_role (id)
		');
		$this->addSql('
			ALTER TABLE son_role_permission
				ADD CONSTRAINT FK_DCF8796FD60322AC FOREIGN KEY (role_id) REFERENCES son_role (id)
		');
		$this->addSql('
			ALTER TABLE son_role_permission
				ADD CONSTRAINT FK_DCF8796FFED90CCA FOREIGN KEY (permission_id) REFERENCES son_permission (id)
		');
		$this->addSql('
			ALTER TABLE son_sharing_permissions
				ADD CONSTRAINT FK_A9C9F7B48F15050 FOREIGN KEY (sharing_id) REFERENCES son_sharing (id)
		');
		$this->addSql('
			ALTER TABLE son_sharing_permissions
				ADD CONSTRAINT FK_A9C9F7BFED90CCA FOREIGN KEY (permission_id) REFERENCES son_permission (id)
		');
		$this->addSql('
			ALTER TABLE fos_user_groups
				ADD CONSTRAINT FK_DA37EFBFA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)
		');
		$this->addSql('
			ALTER TABLE fos_user_groups
				ADD CONSTRAINT FK_DA37EFBFFE54D947 FOREIGN KEY (group_id) REFERENCES fos_group (id)
		');
		$this->addSql('
			ALTER TABLE orm_config
				DROP FOREIGN KEY FK_E6087E3325F94802
		');
		$this->addSql('
			ALTER TABLE orm_config
				ADD CONSTRAINT FK_E6087E3325F94802 FOREIGN KEY (modified_by) REFERENCES fos_user (id)
		');
		$this->addSql(
			'ALTER TABLE orm_filetype
				DROP FOREIGN KEY FK_857BC06025F94802
		');
		$this->addSql('
			ALTER TABLE orm_filetype
				ADD CONSTRAINT FK_857BC06025F94802 FOREIGN KEY (modified_by) REFERENCES fos_user (id)
		');
		$this->addSql('
			ALTER TABLE orm_logo
				DROP FOREIGN KEY FK_DCFDADED25F94802
		');
		$this->addSql('
			ALTER TABLE orm_logo
				ADD CONSTRAINT FK_DCFDADED25F94802 FOREIGN KEY (modified_by) REFERENCES fos_user (id)
		');
		$this->addSql('
			DROP TABLE `user`
		');
		$this->addSql('
			DROP TABLE `user2groups`
		');
	}
	
	/**
	 * @param Schema $schema
	 */
	public function postUp(Schema $schema) {
		
		// get entity manager
		$em = $this->container->get('doctrine.orm.entity_manager');
		
		// get repositories
		$repositoryGroup = $em->getRepository('AppBundle:Group');
		$repositoryUser = $em->getRepository('AppBundle:User');
		
		// create roles
		$superAdminRole = new Role('ROLE_SUPER_ADMIN');
		$adminRole = new Role('ROLE_ADMIN');
		$userRole = new Role('ROLE_USER');
		$jiGroupRole = new Role('ROLE_JI_GROUP');
		
		// add roles to hierachy
		$superAdminRole->addChild($adminRole);
		$adminRole->addChild($userRole);
		
		// persist roles
		$em->persist($superAdminRole);
		$em->persist($adminRole);
		$em->persist($userRole);
		$em->persist($jiGroupRole);
		// flush roles
		$em->flush();
		
		// get groups
		$groups = $repositoryGroup->findAll();
		// set role
		foreach($groups as $group) {
			$group->addRole($jiGroupRole);
		}
		
		// get admin user
		$admin = $repositoryUser->findOneById(1);
		$admin->addRole($superAdminRole);
		
		// flush entities
		$em->flush();
	}
	
	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema) {
		
		$this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
		
		// get migration object
		$jiMigration = new DbMigrateSecurity($this->connection);
		
		// add SQL from ORM
		$this->addSql('
			ALTER TABLE fos_group_groups
				DROP FOREIGN KEY FK_70177A5D727ACA70
		');
		$this->addSql('
			ALTER TABLE fos_group_groups
				DROP FOREIGN KEY FK_70177A5DDD62C21B
		');
		$this->addSql('
			ALTER TABLE fos_user_groups
				DROP FOREIGN KEY FK_DA37EFBFFE54D947
		');
		$this->addSql('
			ALTER TABLE son_role_permission
				DROP FOREIGN KEY FK_DCF8796FFED90CCA
		');
		$this->addSql('
			ALTER TABLE son_sharing_permissions
				DROP FOREIGN KEY FK_A9C9F7BFED90CCA
		');
		$this->addSql('
			ALTER TABLE son_role_roles
				DROP FOREIGN KEY FK_E67BD858727ACA70
		');
		$this->addSql('
			ALTER TABLE son_role_roles
				DROP FOREIGN KEY FK_E67BD858DD62C21B
		');
		$this->addSql('
			ALTER TABLE son_role_permission
				DROP FOREIGN KEY FK_DCF8796FD60322AC
		');
		$this->addSql('
			ALTER TABLE son_sharing_permissions
				DROP FOREIGN KEY FK_A9C9F7B48F15050
		');
		$this->addSql('
			ALTER TABLE orm_config
				DROP FOREIGN KEY FK_E6087E3325F94802
		');
		$this->addSql('
			ALTER TABLE orm_filetype
				DROP FOREIGN KEY FK_857BC06025F94802
		');
		$this->addSql('
			ALTER TABLE orm_logo
				DROP FOREIGN KEY FK_DCFDADED25F94802
		');
		$this->addSql('
			ALTER TABLE fos_user_groups
				DROP FOREIGN KEY FK_DA37EFBFA76ED395
		');
		$this->addSql('
			CREATE TABLE `groups` (
			  `id` int(11) NOT NULL,
			  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
			  `parent` int(11) NOT NULL,
			  `valid` tinyint(1) NOT NULL,
			  `modified_by` int(11) NOT NULL DEFAULT \'0\',
			  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY(id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		');
		$result = $jiMigration->migrateGroupsDown();
		$this->abortIf(!$result['retval'], implode(PHP_EOL, $result['result']));
		$this->addSql($result['result']);
		$this->addSql('
			DROP TABLE fos_group
		');
		$this->addSql('
			DROP TABLE fos_group_groups
		');
		$this->addSql('
			DROP TABLE son_permission
		');
		$this->addSql('
			DROP TABLE son_role
		');
		$this->addSql('
			DROP TABLE son_role_roles
		');
		$this->addSql('
			DROP TABLE son_role_permission
		');
		$this->addSql('
			DROP TABLE son_sharing
		');
		$this->addSql('
			DROP TABLE son_sharing_permissions
		');
		$this->addSql('
			CREATE TABLE `user` (
			  `id` int(11) NOT NULL,
			  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
			  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			  `email` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
			  `active` tinyint(1) NOT NULL,
			  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY(id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		');
		$this->addSql('
			CREATE TABLE `user2groups` (
			  `user_id` int(11) NOT NULL,
			  `group_id` int(11) NOT NULL,
			  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY(user_id, group_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		');
		$result = $jiMigration->migrateUsersDown();
		$this->abortIf(!$result['retval'], implode(PHP_EOL, $result['result']));
		$this->addSql($result['result']);
		$this->addSql('
			ALTER TABLE orm_config
				ADD CONSTRAINT FK_E6087E3325F94802 FOREIGN KEY (modified_by) REFERENCES `user` (id)
		');
		$this->addSql('
			ALTER TABLE orm_filetype
				ADD CONSTRAINT FK_857BC06025F94802 FOREIGN KEY (modified_by) REFERENCES `user` (id)
		');
		$this->addSql('
			ALTER TABLE orm_logo
				ADD CONSTRAINT FK_DCFDADED25F94802 FOREIGN KEY (modified_by) REFERENCES `user` (id)
		');
		$this->addSql('
			DROP TABLE fos_user
		');
		$this->addSql('
			DROP TABLE fos_user_groups
		');
	}
}
