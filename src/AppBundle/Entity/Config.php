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


/**
 * @ORM\Entity
 * @ORM\Table(name="orm_config")
 * @ORM\HasLifecycleCallbacks
 */
class Config {
	
	/**
	 * @ORM\Column(type="string", length=50)
	 * @ORM\Id
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="text")
	 */
	private $value;
	
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $comment;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $valid;
	
	/**
	 * @ORM\Column(type="datetime", name="last_modified")
	 */
	private $lastModified;
	
	// foreign keys
	/**
	 * @ORM\ManyToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="modified_by", referencedColumnName="id")
	 */
	private $modifiedBy;
	
	
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
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
	 * @return Config
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
	 * Set value
	 *
	 * @param string $value
	 *
	 * @return Config
	 */
	public function setValue($value) {
		$this->value = $value;
		
		return $this;
	}
	
	/**
	 * Get value
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * Set comment
	 *
	 * @param string $comment
	 *
	 * @return Config
	 */
	public function setComment($comment) {
		$this->comment = $comment;
		
		return $this;
	}
	
	/**
	 * Get comment
	 *
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}
	
	/**
	 * Set valid
	 *
	 * @param boolean $valid
	 *
	 * @return Navi
	 */
	public function setValid($valid) {
		$this->valid = $valid;
		
		return $this;
	}
	
	/**
	 * Get valid
	 *
	 * @return boolean
	 */
	public function getValid() {
		return $this->valid;
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
	 * Set modified by
	 *
	 * @param \AppBundle\Entity\User $modifiedBy
	 *
	 * @return Config
	 */
	public function setModifiedBy(User $modifiedBy = null) {
		$this->modifiedBy = $modifiedBy;
		
		return $this;
	}
	
	/**
	 * Get modified by
	 *
	 * @return \AppBundle\Entity\User
	 */
	public function getModifiedBy() {
		return $this->modifiedBy;
	}
	
	
	/*
	 * Methods
	 */
}
