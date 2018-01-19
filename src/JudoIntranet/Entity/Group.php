<?php

/*
 * Implementation inspired by Group entity in FOSUserBundle, written by Johannes M. Schmitt <schmittjoh@gmail.com>
 */

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
use Sonatra\Component\Security\Model\GroupInterface;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="orm_group")
 * @ORM\HasLifecycleCallbacks
 */
class Group implements GroupInterface {
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
    
    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="array")
     */
    protected $roles;
	
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
	 * @ORM\ManyToMany(targetEntity="Group", mappedBy="children", fetch="EAGER")
	 */
	private $parents;
	
	/**
	 * groups are members of groups
	 * @ORM\ManyToMany(targetEntity="Group", inversedBy="parents", fetch="EAGER")
	 * @ORM\JoinTable(name="orm_group_groups",
	 *      joinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="child_id", referencedColumnName="id")}
	 * )
	 */
	private $children;
	
	
	
	/**
	 * Constructor
     *
     * @param string $name
     * @param array $roles
	 */
    public function __construct($name, $roles = array()) {
		
        // setup name and roles
        $this->name = $name;
        $this->roles = $roles;
        
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
		return $this->getName();
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
			$this->children[] = $child;
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
		if(false !== $key = array_search($child, $this->children, true)) {
			unset($this->children[$key]);
			$this->children = array_values($this->children);
		}
		
		return $this;
	}
	
	/**
	 * get children
	 * 
	 * @return ArrayCollection
	 */
	public function getChildren() {
		return $this->children;
	}
	
	/**
	 * get parents
	 *
	 * @return ArrayCollection
	 */
	public function getParents() {
		return $this->parents;
	}
    
    /**
     * add role
     *
     * @param string $role
     * @return Group
     */
    public function addRole($role) {
        if(!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }
        
        return $this;
    }
    
    /**
     * get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * checks if has role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role) {
        return in_array(strtoupper($role), $this->roles, true);
    }
    
    /**
     * get roles
     *
     * @return array
     */
    public function getRoles() {
        return $this->roles;
    }
    
    /**
     * remove role
     *
     * @param string $role
     * @return Group
     */
    public function removeRole($role) {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
        
        return $this;
    }
    
    /**
     * set name
     *
     * @param string $name
     * @return Group
     */
    public function setName($name) {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * set roles
     *
     * @param array $roles
     * @return Group
     */
    public function setRoles(array $roles) {
        $this->roles = $roles;
        
        return $this;
    }
}
