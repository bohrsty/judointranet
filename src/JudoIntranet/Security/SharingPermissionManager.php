<?php

/*
 * This file is part of the JudoIntranet project.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet\Security;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Fxp\Component\Security\Model\GroupInterface;
use JudoIntranet\Entity\Permission;
use JudoIntranet\Entity\Sharing;
use Fxp\Component\Security\Exception\SecurityException;
use Fxp\Component\Security\Identity\GroupSecurityIdentity;
use Fxp\Component\Security\Identity\RoleSecurityIdentity;
use Fxp\Component\Security\Identity\SubjectIdentity;
use Fxp\Component\Security\Identity\UserSecurityIdentity;
use Fxp\Component\Security\Model\RoleHierarchicalInterface;
use Fxp\Component\Security\Model\SharingInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class SharingPermissionManager {
    
    /**
     * @var EntityManagerInterface $em
     */
    private $em;
    
    /**
     * constructor
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        
        // set entity manager
        $this->em = $em;
    }
    
    /**
     * share($object, $for)
     * creates a share for $for on $object
     *
     * @param mixed $object the object or class name to share
     * @param mixed $for the security entity to share for
     * @return SharingInterface
     * @throws SecurityException
     */
    public function share($object, $for) {

        // check object
        if(is_object($object)){
            $objectClass = ClassUtils::getClass($object);
        }
        if(isset($objectClass) && !$this->em->getMetadataFactory()->isTransient($objectClass)) {
            $objectIdentity = SubjectIdentity::fromObject($object);
        } else {
            $objectIdentity = SubjectIdentity::fromClassname($object);
        }

        // check security entity
        if($for instanceof UserInterface) {
            $securityIdentity = UserSecurityIdentity::fromAccount($for);
        } elseif($for instanceof GroupInterface) {
            $securityIdentity = GroupSecurityIdentity::fromAccount($for);
        } elseif($for instanceof RoleHierarchicalInterface) {
            $securityIdentity = RoleSecurityIdentity::fromAccount($for);
        } else {
            $securityIdentity = null;
        }
        if(is_null($securityIdentity)) {
            throw new SecurityException('No valid SecurityIdentity');
        }

        // create and save sharing
        $sharing = (new Sharing())
            ->setSubjectClass($objectIdentity->getType())
            ->setSubjectId($objectIdentity->getIdentifier())
            ->setIdentityClass($securityIdentity->getType())
            ->setIdentityName($securityIdentity->getIdentifier());
        $this->em->persist($sharing);
        $this->em->flush();
        
        // return
        return $sharing;
    }
    
    
    /**
     * addPermission($operation, $class, $field)
     * add the defined permission
     *
     * @param string $operation the operation of the permission
     * @param string $class the class to add the permission for (default: '')
     * @param string $field the field of $class to add the permission for (default: '')
     * @return Permission
     * @throws SecurityException
     */
    public function addPermission($operation, $class = '', $field = '') {
        
        // check field and class
        if($field === '' && $class === '') {
            $permission = (new Permission())
                ->setOperation($operation);
        } elseif($field === '' && $class !== '') {
            $permission = (new Permission())
                ->setOperation($operation)
                ->setClass($class);
        } elseif($field !== '' && $class !== '') {
            $permission = (new Permission())
                ->setOperation($operation)
                ->setClass($class)
                ->setField($field);
        } else {
            $permission = null;
        }
        if(is_null($permission)) {
            throw new SecurityException('No valid Permission');
        }
        
        // persist and save
        $this->em->persist($permission);
        $this->em->flush();
        
        // return
        return $permission;
    }
    
    
    /**
     * grant($permission, $on)
     * grants $permission on the security entity $on
     *
     * @param Permission $permission the permission to grant
     * @param mixed $on the security entity to grant permission on
     * @return void
     * @throws SecurityException
     */
    public function grant($permission, $on) {
        
        // check security entity
        if(
            !$on instanceof UserInterface
            && !$on instanceof GroupInterface
            && !$on instanceof RoleHierarchicalInterface
        ) {
            throw new SecurityException('Invalid security entity');
        }
        
        // add permission
        $on->addPermission($permission);
        
        // persist and save
        $this->em->persist($on);
        $this->em->flush();
    }
    
    /**
     * isShared($object, $for)
     * checks if $object is shared for $for
     *
     * @param mixed $object the object or class name to check the share on
     * @param mixed $for the security entity to check the share for
     * @return bool
     * @throws SecurityException
     */
    public function isShared($object, $for) {
        
        // check object
        if(is_object($object)){
            $objectClass = ClassUtils::getClass($object);
        }
        if(isset($objectClass) && !$this->em->getMetadataFactory()->isTransient($objectClass)) {
            $objectIdentity = SubjectIdentity::fromObject($object);
        } else {
            $objectIdentity = SubjectIdentity::fromClassname($object);
        }
        
        // check security entity
        if($for instanceof UserInterface) {
            $securityIdentity = UserSecurityIdentity::fromAccount($for);
        } elseif($for instanceof GroupInterface) {
            $securityIdentity = GroupSecurityIdentity::fromAccount($for);
        } elseif($for instanceof RoleHierarchicalInterface) {
            $securityIdentity = RoleSecurityIdentity::fromAccount($for);
        } else {
            $securityIdentity = null;
        }
        if(is_null($securityIdentity)) {
            throw new SecurityException('No valid SecurityIdentity');
        }
        
        // get sharing repository
        $sharingRepository = $this->em->getRepository('JudoIntranet:Sharing');
        $sharing = $sharingRepository->findOneBy(array(
            'subjectClass' => $objectIdentity->getType(),
            'subjectId' => $objectIdentity->getIdentifier(),
            'identityClass' => $securityIdentity->getType(),
            'identityName' => $securityIdentity->getIdentifier(),
        ));
        
        // return
        return !is_null($sharing);
    }
}
