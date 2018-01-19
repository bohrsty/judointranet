<?php

namespace JudoIntranetMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * rename sonatra tables to orm (sonatra -> fxp)
 */
class Version20180119162704 extends AbstractMigration {
    
    public function up(Schema $schema) {
        // add SQL from ORM
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            ALTER TABLE son_role_permission
                DROP FOREIGN KEY FK_DCF8796FFED90CCA
        ');
        $this->addSql('
            ALTER TABLE son_sharing_permissions
                DROP FOREIGN KEY FK_A9C9F7BFED90CCA
        ');
        $this->addSql('
            ALTER TABLE son_role_permission
                DROP FOREIGN KEY FK_DCF8796FD60322AC
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
            ALTER TABLE son_sharing_permissions
                DROP FOREIGN KEY FK_A9C9F7B48F15050
        ');
        $this->addSql('
            CREATE TABLE orm_permission (
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
            CREATE TABLE orm_role (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                valid TINYINT(1) NOT NULL,
                last_modified DATETIME NOT NULL,
                UNIQUE INDEX UNIQ_6F1ABD945E237E06 (name),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE orm_role_roles (
                parent_id INT NOT NULL,
                child_id INT NOT NULL,
                INDEX IDX_2E89614D727ACA70 (parent_id),
                INDEX IDX_2E89614DDD62C21B (child_id),
                PRIMARY KEY(parent_id, child_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE orm_role_permission (
                role_id INT NOT NULL,
                permission_id INT NOT NULL,
                INDEX IDX_A1E19A75D60322AC (role_id),
                INDEX IDX_A1E19A75FED90CCA (permission_id),
                PRIMARY KEY(role_id, permission_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE orm_sharing (
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
                INDEX subject_class_idx (subject_class),
                INDEX subject_id_idx (subject_id),
                INDEX identity_class_idx (identity_class),
                INDEX identity_name_idx (identity_name),
                UNIQUE INDEX unique_sharing_idx (subject_class, subject_id, identity_class, identity_name),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE orm_sharing_permissions (
                sharing_id INT NOT NULL,
                permission_id INT NOT NULL,
                INDEX IDX_2B28E2E048F15050 (sharing_id),
                INDEX IDX_2B28E2E0FED90CCA (permission_id),
                PRIMARY KEY(sharing_id, permission_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        // migrate data from son to orm
        $this->addSql('
			INSERT INTO orm_permission
			SELECT
                id, operation, contexts, class, field, last_modified
            FROM son_permission
		');
        $this->addSql('
			INSERT INTO orm_role
			SELECT
                id, name, valid, last_modified
            FROM son_role
		');
        $this->addSql('
			INSERT INTO orm_role_roles
			SELECT
                parent_id, child_id
            FROM son_role_roles
		');
        $this->addSql('
			INSERT INTO orm_role_permission
			SELECT
                role_id, permission_id
            FROM son_role_permission
		');
        $this->addSql('
			INSERT INTO orm_sharing
			SELECT
                id, subject_class, subject_id, identity_class, identity_name, enabled, roles, started_at, ended_at, last_modified
            FROM son_sharing
		');
        $this->addSql('
			INSERT INTO orm_sharing_permissions
			SELECT
                sharing_id, permission_id
            FROM son_sharing_permissions
		');
        $this->addSql('
            ALTER TABLE orm_role_roles
                ADD CONSTRAINT FK_2E89614D727ACA70 FOREIGN KEY (parent_id) REFERENCES orm_role (id)
        ');
        $this->addSql('
            ALTER TABLE orm_role_roles
                ADD CONSTRAINT FK_2E89614DDD62C21B FOREIGN KEY (child_id) REFERENCES orm_role (id)
        ');
        $this->addSql('
            ALTER TABLE orm_role_permission
                ADD CONSTRAINT FK_A1E19A75D60322AC FOREIGN KEY (role_id) REFERENCES orm_role (id)
        ');
        $this->addSql('
            ALTER TABLE orm_role_permission
                ADD CONSTRAINT FK_A1E19A75FED90CCA FOREIGN KEY (permission_id) REFERENCES orm_permission (id)
        ');
        $this->addSql('
            ALTER TABLE orm_sharing_permissions
                ADD CONSTRAINT FK_2B28E2E048F15050 FOREIGN KEY (sharing_id) REFERENCES orm_sharing (id)
        ');
        $this->addSql('
            ALTER TABLE orm_sharing_permissions
                ADD CONSTRAINT FK_2B28E2E0FED90CCA FOREIGN KEY (permission_id) REFERENCES orm_permission (id)
        ');
        $this->addSql('
            DROP TABLE son_permission
        ');
        $this->addSql('
            DROP TABLE son_role
        ');
        $this->addSql('
            DROP TABLE son_role_permission
        ');
        $this->addSql('
            DROP TABLE son_role_roles
        ');
        $this->addSql('
            DROP TABLE son_sharing
        ');
        $this->addSql('
            DROP TABLE son_sharing_permissions
        ');
        $this->addSql('
            ALTER TABLE orm_config
                CHANGE modified_by modified_by INT DEFAULT NULL
        ');
        $this->addSql('
            ALTER TABLE orm_filetype
                CHANGE modified_by modified_by INT DEFAULT NULL
        ');
        $this->addSql('
            ALTER TABLE orm_logo
                CHANGE modified_by modified_by INT DEFAULT NULL,
                CHANGE filetype filetype INT DEFAULT NULL
        ');
        $this->addSql('
            ALTER TABLE orm_navi
                CHANGE parent parent INT DEFAULT NULL,
                CHANGE file_param file_param VARCHAR(75) DEFAULT NULL,
                CHANGE url url VARCHAR(75) DEFAULT NULL,
                CHANGE required_permission required_permission VARCHAR(1) DEFAULT \'r\' NOT NULL
        ');
        $this->addSql('
            ALTER TABLE orm_user
                CHANGE salt salt VARCHAR(255) DEFAULT NULL
        ');
    }

    public function down(Schema $schema) {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            ALTER TABLE orm_role_permission
                DROP FOREIGN KEY FK_A1E19A75FED90CCA
        ');
        $this->addSql('
            ALTER TABLE orm_sharing_permissions
                DROP FOREIGN KEY FK_2B28E2E0FED90CCA
        ');
        $this->addSql('
            ALTER TABLE orm_role_roles
                DROP FOREIGN KEY FK_2E89614D727ACA70
        ');
        $this->addSql('
            ALTER TABLE orm_role_roles
                DROP FOREIGN KEY FK_2E89614DDD62C21B
        ');
        $this->addSql('
            ALTER TABLE orm_role_permission
                DROP FOREIGN KEY FK_A1E19A75D60322AC
        ');
        $this->addSql('
            ALTER TABLE orm_sharing_permissions
                DROP FOREIGN KEY FK_2B28E2E048F15050
        ');
        $this->addSql('
            CREATE TABLE son_permission (
                id INT AUTO_INCREMENT NOT NULL,
                operation VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci,
                contexts LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\',
                class VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci,
                field VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci,
                last_modified DATETIME NOT NULL,
                UNIQUE INDEX unique_permission_idx (operation, class, field),
                INDEX operation_idx (operation),
                INDEX class_idx (class),
                INDEX field_idx (field),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE son_role (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci,
                valid TINYINT(1) NOT NULL,
                last_modified DATETIME NOT NULL,
                UNIQUE INDEX UNIQ_C49316505E237E06 (name),
                PRIMARY KEY(id)
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
            CREATE TABLE son_role_roles (
                parent_id INT NOT NULL,
                child_id INT NOT NULL,
                INDEX IDX_E67BD858727ACA70 (parent_id),
                INDEX IDX_E67BD858DD62C21B (child_id),
                PRIMARY KEY(parent_id, child_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE son_sharing (
                id INT AUTO_INCREMENT NOT NULL,
                subject_class VARCHAR(244) NOT NULL COLLATE utf8_unicode_ci,
                subject_id VARCHAR(36) NOT NULL COLLATE utf8_unicode_ci,
                identity_class VARCHAR(244) NOT NULL COLLATE utf8_unicode_ci,
                identity_name VARCHAR(244) NOT NULL COLLATE utf8_unicode_ci,
                enabled TINYINT(1) NOT NULL,
                roles LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\',
                started_at DATETIME DEFAULT NULL,
                ended_at DATETIME DEFAULT NULL,
                last_modified DATETIME NOT NULL,
                UNIQUE INDEX unique_sharing_idx (subject_class, subject_id, identity_class, identity_name),
                INDEX subject_class_idx (subject_class),
                INDEX subject_id_idx (subject_id),
                INDEX identity_class_idx (identity_class),
                INDEX identity_name_idx (identity_name),
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
        // migrate data from orm to son
        $this->addSql('
			INSERT INTO son_permission
			SELECT
                id, operation, contexts, class, field, last_modified
            FROM orm_permission
		');
        $this->addSql('
			INSERT INTO son_role
			SELECT
                id, name, valid, last_modified
            FROM orm_role
		');
        $this->addSql('
			INSERT INTO son_role_roles
			SELECT
                parent_id, child_id
            FROM orm_role_roles
		');
        $this->addSql('
			INSERT INTO son_role_permission
			SELECT
                role_id, permission_id
            FROM orm_role_permission
		');
        $this->addSql('
			INSERT INTO son_sharing
			SELECT
                id, subject_class, subject_id, identity_class, identity_name, enabled, roles, started_at, ended_at, last_modified
            FROM orm_sharing
		');
        $this->addSql('
			INSERT INTO son_sharing_permissions
			SELECT
                sharing_id, permission_id
            FROM orm_sharing_permissions
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
            ALTER TABLE son_role_roles
                ADD CONSTRAINT FK_E67BD858727ACA70 FOREIGN KEY (parent_id) REFERENCES son_role (id)
        ');
        $this->addSql('
            ALTER TABLE son_role_roles
                ADD CONSTRAINT FK_E67BD858DD62C21B FOREIGN KEY (child_id) REFERENCES son_role (id)
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
            DROP TABLE orm_permission
        ');
        $this->addSql('
            DROP TABLE orm_role
        ');
        $this->addSql('
            DROP TABLE orm_role_roles
        ');
        $this->addSql('
            DROP TABLE orm_role_permission
        ');
        $this->addSql('
            DROP TABLE orm_sharing
        ');
        $this->addSql('
            DROP TABLE orm_sharing_permissions
        ');
        $this->addSql('
            ALTER TABLE orm_config
                CHANGE modified_by modified_by INT DEFAULT NULL
        ');
        $this->addSql('
            ALTER TABLE orm_filetype
                CHANGE modified_by modified_by INT DEFAULT NULL
        ');
        $this->addSql('
            ALTER TABLE orm_logo
                CHANGE modified_by modified_by INT DEFAULT NULL,
                CHANGE filetype filetype INT DEFAULT NULL
        ');
        $this->addSql('
            ALTER TABLE orm_navi
                CHANGE parent parent INT DEFAULT NULL
        ');
    }
}
