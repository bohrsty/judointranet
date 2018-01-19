<?php

/*
 * Implementation inspired by Group entity in FOSUserBundle, written by
 * Thibault Duplessis <thibault.duplessis@gmail.com> and Johannes M. Schmitt <schmittjoh@gmail.com>
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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sonatra\Component\Security\Model\GroupInterface;
use Sonatra\Component\Security\Model\UserInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="orm_user")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface {
	
    /*
     * variables
     */
    const ROLE_DEFAULT = 'ROLE_PUBLIC';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="datetime", name="last_modified")
	 */
	private $lastModified;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Group", fetch="EAGER")
	 * @ORM\JoinTable(name="orm_user_groups",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
	 * )
	 */
	protected $groups;
    
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $username;
    
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $email;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $salt;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $password;
    
    /**
     * @ORM\Column(type="array")
     */
    protected $roles;
	
	
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
	    // setup enabled and roles
        $this->enabled = false;
        $this->roles = array();
        
        // setup groups
        $this->groups = new ArrayCollection();
	    
		// setup modified
		if(is_null($this->getLastModified())) {
			$this->setLastModified(new \DateTime());
		}
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
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return User
	 */
	public function setName($name) {
		$this->name = $name;
		
		return $this;
	}
	
	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Set lastModified
	 *
	 * @param \DateTime $lastModified
	 *
	 * @return User
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
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }
        
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->id,
            $this->email,
            $this->emailCanonical,
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        
        if (13 === count($data)) {
            // Unserializing a User object from 1.3.x
            unset($data[4], $data[5], $data[6], $data[9], $data[10]);
            $data = array_values($data);
        } elseif (11 === count($data)) {
            // Unserializing a User from a dev version somewhere between 2.0-alpha3 and 2.0-beta1
            unset($data[4], $data[7], $data[8]);
            $data = array_values($data);
        }
        
        list(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->id,
            $this->email,
            $this->emailCanonical
            ) = $data;
    }
    
    /**
     * erase credentials
     *
     * @return void
     */
    public function eraseCredentials() {
        $this->password = '';
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
     * get username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }
    
    /**
     * get salt
     *
     * @return string
     */
    public function getSalt() {
        return $this->salt;
    }
    
    /**
     * get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }
    
    /**
     * get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }
    
    /**
     * get roles
     *
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->roles;
        
        foreach($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }
        
        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;
        
        return array_unique($roles);
    }
    
    /**
     * check if has role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role) {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }
    
    /**
     * implement interface
     *
     * @return bool
     */
    public function isAccountNonExpired() {
        return true;
    }
    
    /**
     * implement interface
     *
     * @return bool
     */
    public function isAccountNonLocked() {
        return true;
    }
    
    /**
     * implement interface
     *
     * @return bool
     */
    public function isCredentialsNonExpired() {
        return true;
    }
    
    /**
     * get enabled
     *
     * @return bool
     */
    public function isEnabled() {
        return $this->enabled;
    }
    
    /**
     * implement interface
     *
     * @return bool
     */
    public function isSuperAdmin() {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }
    
    /**
     * remove role
     *
     * @param string $role
     * @return User
     */
    public function removeRole($role) {
        if(false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
        
        return $this;
    }
    
    /**
     * set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;
        
        return $this;
    }
    
    /**
     * set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;
        
        return $this;
    }
    
    /**
     * set enabled
     *
     * @param bool $boolean
     * @return User
     */
    public function setEnabled($boolean) {
        $this->enabled = (bool) $boolean;
        
        return $this;
    }
    
    /**
     * set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;
        
        return $this;
    }
    
    /**
     * set super admin
     *
     * @param boolean $boolean
     * @return User
     */
    public function setSuperAdmin($boolean) {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }
        
        return $this;
    }
    
    /**
     * set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles) {
        $this->roles = array();
        
        foreach ($roles as $role) {
            $this->addRole($role);
        }
        
        return $this;
    }
    
    /**
     * get groups
     *
     * @return ArrayCollection
     */
    public function getGroups() {
        return $this->groups ?: $this->groups = new ArrayCollection();
    }
    
    /**
     * get group names
     *
     * @return array
     */
    public function getGroupNames() {
        $names = array();
        foreach($this->getGroups() as $group) {
            $names[] = $group->getName();
        }
        
        return $names;
    }
    
    /**
     * check if has group
     *
     * @param string $name
     * @return bool
     */
    public function hasGroup($name) {
        return in_array($name, $this->getGroupNames());
    }
    
    /**
     * add group
     *
     * @param GroupInterface
     * @return User
     */
    public function addGroup(GroupInterface $group) {
        if(!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }
        
        return $this;
    }
    
    /**
     * remove group
     *
     * @param GroupInterface
     * @return User
     */
    public function removeGroup(GroupInterface $group) {
        if($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }
        
        return $this;
    }
    
    /**
     * to string
     *
     * @return string
     */
    public function __toString() {
        return (string) $this->getUsername();
    }
}
