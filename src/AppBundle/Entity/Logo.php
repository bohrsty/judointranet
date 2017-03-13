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
use AppBundle\Entity\User;
use AppBundle\Entity\FileType;

/**
 * @ORM\Entity
 * @ORM\Table(name="orm_logo")
 * @ORM\HasLifecycleCallbacks
 */
class Logo {
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string", length=150)
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="blob")
	 */
	private $data;
	
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
	 * @ORM\ManyToOne(targetEntity="FileType")
	 * @ORM\JoinColumn(name="filetype", referencedColumnName="id")
	 */
	private $fileType;
	
	

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return Logo
	 */
	public function setName(string $name) {
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
	 * Set data
	 *
	 * @param string $data
	 *
	 * @return Logo
	 */
	public function setData(string $data) {
		$this->data = $data;
		
		return $this;
	}
	
	/**
	 * Get data
	 *
	 * @return string
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * Set file type
	 *
	 * @param \AppBundle\Entity\FileType $fileType
	 *
	 * @return Logo
	 */
	public function setFileType(FileType $fileType = null) {
		$this->fileType = $fileType;
		
		return $this;
	}
	
	/**
	 * Get file type
	 *
	 * @return \AppBundle\Entity\FileType
	 */
	public function getFileType() {
		return $this->fileType;
	}
	
	/**
	 * Set valid
	 *
	 * @param boolean $valid
	 *
	 * @return Logo
	 */
	public function setValid(bool $valid) {
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
	 * @return Logo
	 */
	public function setLastModified(\DateTime $lastModified) {
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
	 * @return Logo
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
	 * get data base64 encoded as string for HTML img tag source
	 * 
	 * @return string the base64 encoded data
	 */
	public function getAsImgSrc() {
		
		return 'base64:'.base64_encode($this->getData());
	}
}
