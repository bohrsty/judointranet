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

/**
 * @ORM\Entity
 * @ORM\Table(name="orm_filetype")
 * @ORM\HasLifecycleCallbacks
 */
class FileType {

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
	 * @ORM\Column(type="string", length=100, name="mime_type")
	 */
	private $mimeType;

	/**
	 * @ORM\Column(type="string", length=10)
	 */
	private $extension;

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
	 * @return FileType
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
	 * Set mime type
	 *
	 * @param string $mimeType
	 *
	 * @return FileType
	 */
	public function setMimeType(string $mimeType) {
		$this->mimeType = $mimeType;

		return $this;
	}

	/**
	 * Get mime type
	 *
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	/**
	 * Set extension
	 *
	 * @param string $extension
	 *
	 * @return FileType
	 */
	public function setExtension(string $extension) {
		$this->extension = $extension;

		return $this;
	}

	/**
	 * Get extension
	 *
	 * @return string
	 */
	public function getExtension() {
		return $this->extension;
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
	 * @param User $modifiedBy
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
	 * @return User
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
}
