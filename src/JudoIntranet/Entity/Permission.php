<?php

/*
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonatra\Component\Security\Model\Permission as BasePermission;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="son_permission")
 * @ORM\HasLifecycleCallbacks
 */
class Permission extends BasePermission {
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="datetime", name="last_modified")
	 */
	private $lastModified;
	
	/**
	 * roles
	 * @ORM\ManyToMany(targetEntity="Role", mappedBy="permissions")
	 */
	protected $roles;
	
	/**
	 * roles
	 * @ORM\ManyToMany(targetEntity="Sharing", mappedBy="permissions")
	 */
	protected $sharingEntries;
	
	
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
		// call parent constructor
		parent::__construct();
		
		// setup modified
		if(is_null($this->getLastModified())) {
			$this->setLastModified(new \DateTime());
		}
		
		// setup parent and children
		$this->roles = new ArrayCollection();
		$this->sharingEntries = new ArrayCollection();
	}
	
	
	/**
	 * update the last modified timestamp
	 *
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function updateLastModified() {
		$this->setLastModified(new \DateTime());
	}
	
	/**
	 * Set lastModified
	 *
	 * @param \DateTime $lastModified
	 *
	 * @return Permission
	 */
	public function setLastModified($lastModified) {
		$this->lastModified = $lastModified;
		
		return $this;
	}
	
	/**
	 * Get lastModified
	 *
	 * @return \DateTime
	 */
	public function getLastModified() {
		return $this->lastModified;
	}
}
