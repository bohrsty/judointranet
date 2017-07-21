<?php

/*
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;
use Sonatra\Component\Security\Model\GroupInterface;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_group")
 * @ORM\HasLifecycleCallbacks
 */
class Group extends BaseGroup implements GroupInterface {
	
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
	 * groups are members of groups
	 * @ORM\ManyToMany(targetEntity="Group", mappedBy="children")
	 */
	private $parents;
	
	/**
	 * groups are members of groups
	 * @ORM\ManyToMany(targetEntity="Group", inversedBy="parents")
	 * @ORM\JoinTable(name="fos_group_groups",
	 *      joinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="child_id", referencedColumnName="id")}
	 * )
	 */
	private $children;
	
	
	
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
		$this->parents = new ArrayCollection();
		$this->children = new ArrayCollection();
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
	 * implement interface
	 */
	public function getGroup() {
		$this->getName();
	}
	
	/**
	 * Set lastModified
	 *
	 * @param \DateTime $lastModified
	 *
	 * @return Group
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
	
	/**
	 * Has children
	 * 
	 * @param Group $child
	 * @return bool
	 */
	public function hasChild($child) {
		return in_array($child, $this->children, true);
	}
	
	/**
	 * Has parent
	 *
	 * @param Group $parent
	 * @return bool
	 */
	public function hasParent($parent) {
		return in_array($parent, $this->parents, true);
	}
	
	/**
	 * add child
	 * 
	 * @param Group $child
	 * @return Group
	 */
	public function addChild($child) {
		
		if (!$this->hasChild($child)) {
			$this->children[] = $role;
		}
		
		return $this;
	}
	
	/**
	 * remove child
	 * 
	 * @param Group $child
	 * @return Group
	 */
	public function removeChild($child) {
		if (false !== $key = array_search($child, $this->children, true)) {
			unset($this->children[$key]);
			$this->children = array_values($this->children);
		}
		
		return $this;
	}
	
	/**
	 * get children
	 * 
	 * @return \Doctrine\Common\Collections\ArrayCollection\ArrayCollection
	 */
	public function getChildren() {
		return $this->children;
	}
	
	/**
	 * get parents
	 *
	 * @return \Doctrine\Common\Collections\ArrayCollection\ArrayCollection
	 */
	public function getParents() {
		return $this->parents;
	}
}
