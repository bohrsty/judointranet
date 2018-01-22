<?php

namespace JudoIntranetMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use JudoIntranet\Legacy;
use JudoIntranet\Migrate\DbMigrateSecurity;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * migrate navi permissions to sonatra security
 */
class Version20180106102936 extends AbstractMigration implements ContainerAwareInterface {
    
    use ContainerAwareTrait;
    
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
        // correct sharing
        $this->addSql('ALTER TABLE son_sharing DROP COLUMN identity_id');

    }
    
    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema) {
    
        // get migration object
        $jiMigration = new DbMigrateSecurity($this->connection);
        
        // migrate navi
        $result = $jiMigration->migrateNaviPermissionsUp($this->container);
        $this->abortIf(!$result['retval'], is_array($result['result']) ? implode(PHP_EOL, $result['result']) : $result['result']);
    }
    
    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        
        // reset sharing and permission tables
        $this->addSql('
			TRUNCATE TABLE son_role_permission
		');
        $this->addSql('
            DELETE FROM son_sharing_permissions WHERE TRUE
		');
        $this->addSql('
			DELETE FROM son_permission WHERE TRUE
		');
        $this->addSql('
			DELETE FROM son_sharing WHERE TRUE
		');
        
        // "correct" sharing
        $this->addSql('ALTER TABLE son_sharing ADD COLUMN identity_id INT NOT NULL');
    }
    
    /**
     * @param Schema $schema
     */
    public function postDown(Schema $schema) {
    
        // get entity manager
        $em = $this->container->get('doctrine.orm.entity_manager');
        // get repository
        $roleRepository = $em->getRepository('JudoIntranet:Role');
        // get custom public role
        $rolePublic = $roleRepository->findOneByName('ROLE_PUBLIC');
        // delete
        $em->remove($rolePublic);
        $em->flush();
    
        // switch login and logout show to false
        $naviRepository = $em->getRepository('JudoIntranet:Navi');
        $login = $naviRepository->findOneById(2);
        $logout = $naviRepository->findOneById(3);
        $login->setShow(false);
        $logout->setShow(false);
        $em->persist($login);
        $em->persist($logout);
        $em->flush();
    }
}
