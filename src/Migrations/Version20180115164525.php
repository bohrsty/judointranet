<?php

namespace JudoIntranetMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * remove fos user bundle
 */
class Version20180115164525 extends AbstractMigration {
    
    public function up(Schema $schema) {
        // add SQL from ORM
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE orm_group (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
                valid TINYINT(1) NOT NULL,
                last_modified DATETIME NOT NULL,
                UNIQUE INDEX UNIQ_37FDE8E95E237E06 (name),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE orm_group_groups (
                parent_id INT NOT NULL,
                child_id INT NOT NULL,
                INDEX IDX_B829F02C727ACA70 (parent_id),
                INDEX IDX_B829F02CDD62C21B (child_id),
                PRIMARY KEY(parent_id, child_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE orm_user (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(50) NOT NULL,
                last_modified DATETIME NOT NULL,
                username VARCHAR(180) NOT NULL,
                email VARCHAR(180) NOT NULL,
                salt VARCHAR(255) DEFAULT NULL,
                enabled TINYINT(1) NOT NULL,
                password VARCHAR(255) NOT NULL,
                roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
                UNIQUE INDEX UNIQ_B5E0E1B7F85E0677 (username),
                UNIQUE INDEX UNIQ_B5E0E1B7E7927C74 (email),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE orm_user_groups (
                user_id INT NOT NULL,
                group_id INT NOT NULL,
                INDEX IDX_33EC1E8DA76ED395 (user_id),
                INDEX IDX_33EC1E8DFE54D947 (group_id),
                PRIMARY KEY(user_id, group_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        // migrate data from fos to orm
        $this->addSql('
			INSERT INTO orm_user
			SELECT
                id, name, last_modified, username, email, salt, enabled, password, roles
            FROM fos_user
		');
        $this->addSql('
			INSERT INTO orm_group
			SELECT
                id, name, roles, valid, last_modified
            FROM fos_group
		');
        $this->addSql('
			INSERT INTO orm_group_groups
			SELECT
                parent_id, child_id
            FROM fos_group_groups
		');
        $this->addSql('
			INSERT INTO orm_user_groups
			SELECT
                user_id, group_id
            FROM fos_user_groups
		');
        $this->addSql('
            ALTER TABLE orm_group_groups
                ADD CONSTRAINT FK_B829F02C727ACA70 FOREIGN KEY (parent_id) REFERENCES orm_group (id)
        ');
        $this->addSql('
            ALTER TABLE orm_group_groups
                ADD CONSTRAINT FK_B829F02CDD62C21B FOREIGN KEY (child_id) REFERENCES orm_group (id)
        ');
        $this->addSql('
            ALTER TABLE orm_user_groups
                ADD CONSTRAINT FK_33EC1E8DA76ED395 FOREIGN KEY (user_id) REFERENCES orm_user (id)
        ');
        $this->addSql('
            ALTER TABLE orm_user_groups
                ADD CONSTRAINT FK_33EC1E8DFE54D947 FOREIGN KEY (group_id) REFERENCES orm_group (id)
        ');
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
				DROP FOREIGN KEY FK_DA37EFBFA76ED395
		');
        $this->addSql('
            ALTER TABLE orm_config
                DROP FOREIGN KEY FK_E6087E3325F94802
        ');
        $this->addSql('
            ALTER TABLE orm_config
                ADD CONSTRAINT FK_E6087E3325F94802 FOREIGN KEY (modified_by) REFERENCES orm_user (id)
        ');
        $this->addSql('
            ALTER TABLE orm_filetype
                DROP FOREIGN KEY FK_857BC06025F94802
        ');
        $this->addSql('
            ALTER TABLE orm_filetype
                ADD CONSTRAINT FK_857BC06025F94802 FOREIGN KEY (modified_by) REFERENCES orm_user (id)
        ');
        $this->addSql('
            ALTER TABLE orm_logo
                DROP FOREIGN KEY FK_DCFDADED25F94802
        ');
        $this->addSql('
            ALTER TABLE orm_logo
                ADD CONSTRAINT FK_DCFDADED25F94802 FOREIGN KEY (modified_by) REFERENCES orm_user (id)
        ');
        $this->addSql('
			ALTER TABLE fos_user_groups
				DROP FOREIGN KEY FK_DA37EFBFFE54D947
		');
        $this->addSql('
            DROP TABLE fos_group
        ');
        $this->addSql('
            DROP TABLE fos_group_groups
        ');
        $this->addSql('
            DROP TABLE fos_user
        ');
        $this->addSql('
            DROP TABLE fos_user_groups
        ');
    }

    public function down(Schema $schema) {
        
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            ALTER TABLE orm_group_groups
                DROP FOREIGN KEY FK_B829F02C727ACA70
        ');
        $this->addSql('
            ALTER TABLE orm_group_groups
                DROP FOREIGN KEY FK_B829F02CDD62C21B
        ');
        $this->addSql('
            ALTER TABLE orm_user_groups
                DROP FOREIGN KEY FK_33EC1E8DFE54D947
        ');
        $this->addSql('
            ALTER TABLE orm_user_groups
                DROP FOREIGN KEY FK_33EC1E8DA76ED395
        ');
        $this->addSql('
            CREATE TABLE fos_group (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci,
                roles LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\',
                valid TINYINT(1) NOT NULL,
                last_modified DATETIME NOT NULL,
                UNIQUE INDEX UNIQ_4B019DDB5E237E06 (name),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE fos_group_groups (
                parent_id INT NOT NULL,
                child_id INT NOT NULL, INDEX IDX_70177A5D727ACA70 (parent_id),
                INDEX IDX_70177A5DDD62C21B (child_id),
                PRIMARY KEY(parent_id, child_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE fos_user (
                id INT AUTO_INCREMENT NOT NULL,
                username VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci,
                username_canonical VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci,
                email VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci,
                email_canonical VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci,
                enabled TINYINT(1) NOT NULL,
                salt VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci,
                password VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci,
                last_login DATETIME DEFAULT NULL,
                confirmation_token VARCHAR(180) DEFAULT NULL COLLATE utf8_unicode_ci,
                password_requested_at DATETIME DEFAULT NULL,
                roles LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\',
                name VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci,
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
        // migrate data from orm to fos
        $this->addSql('
			INSERT INTO fos_user
			SELECT
                id, username, username AS username_canonical, email, email AS email_canonical, enabled, salt, password, NULL AS last_login, NULL AS confirmation_token, NULL AS password_requested_at, roles, name, last_modified
            FROM orm_user
		');
        $this->addSql('
			INSERT INTO fos_group
			SELECT
                id, name, roles, valid, last_modified
            FROM orm_group
		');
        $this->addSql('
			INSERT INTO fos_group_groups
			SELECT
                parent_id, child_id
            FROM orm_group_groups
		');
        $this->addSql('
			INSERT INTO fos_user_groups
			SELECT
                user_id, group_id
            FROM orm_user_groups
		');
        $this->addSql('
			ALTER TABLE fos_group_groups
				ADD CONSTRAINT FK_70177A5D727ACA70 FOREIGN KEY (parent_id) REFERENCES fos_group (id)
		');
        $this->addSql('
			ALTER TABLE fos_group_groups
				ADD CONSTRAINT FK_70177A5DDD62C21B FOREIGN KEY (child_id) REFERENCES fos_group (id)
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
        $this->addSql('
            ALTER TABLE orm_filetype
                DROP FOREIGN KEY FK_857BC06025F94802');
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
            DROP TABLE orm_group
        ');
        $this->addSql('
            DROP TABLE orm_group_groups
        ');
        $this->addSql('
            DROP TABLE orm_user
        ');
        $this->addSql('
            DROP TABLE orm_user_groups
        ');
    }
}
