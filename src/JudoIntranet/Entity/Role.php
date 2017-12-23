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
use Sonatra\Component\Security\Model\Role as BaseRole;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="son_role")
 * @ORM\HasLifecycleCallbacks
 */
class Role extends BaseRole {
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $valid;
	
	/**
	 * @ORM\Column(type="datetime", name="last_modified")
	 */
	private $lastModified;
	
	/**
	 * roles are members of roles
	 * @ORM\ManyToMany(targetEntity="Role", mappedBy="children")
	 */
	protected $parents;
	
	/**
	 * roles are members of roles
	 * @ORM\ManyToMany(targetEntity="Role", inversedBy="parents")
	 * @ORM\JoinTable(name="son_role_roles",
	 *      joinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="child_id", referencedColumnName="id")}
	 * )
	 */
	protected $children;
	
	/**
	 * permissions of roles
	 * @ORM\ManyToMany(targetEntity="Permission", inversedBy="roles")
	 * @ORM\JoinTable(name="son_role_permission",
	 *      joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="permission_id", referencedColumnName="id")}
	 * )
	 */
	protected $permissions;
	
	
	
	/**
	 * Constructor
	 * 
	 * @param string $name
	 * @param bool $valid
	 */
	public function __construct(string $name, bool $valid = true) {
		
		// call parent constructor
		parent::__construct($name);
		
		// set valid
		$this->setValid($valid);
		
		// setup modified
		if(is_null($this->getLastModified())) {
			$this->setLastModified(new \DateTime());
		}
		
		// setup parent and children
		$this->parents = new ArrayCollection();
		$this->children = new ArrayCollection();
		$this->permissions = new ArrayCollection();
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
	 * Set valid
	 *
	 * @param bool $valid
	 *
	 * @return Role
	 */
	public function setValid($valid) {
		$this->valid = $valid;
		
		return $this;
	}
	
	/**
	 * Get valid
	 *
	 * @return bool
	 */
	public function getValid() {
		return $this->valid;
	}
	
	/**
	 * Set lastModified
	 *
	 * @param \DateTime $lastModified
	 *
	 * @return Role
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
