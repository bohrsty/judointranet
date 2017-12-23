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
use FOS\UserBundle\Model\User as BaseUser;
use Sonatra\Component\Security\Model\UserInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser implements UserInterface {
	
	
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
	 * @ORM\ManyToMany(targetEntity="Group")
	 * @ORM\JoinTable(name="fos_user_groups",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
	 * )
	 */
	protected $groups;
	
	
	
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
}
