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
use Sonatra\Component\Security\Model\Sharing as BaseSharing;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="son_sharing")
 * @ORM\HasLifecycleCallbacks
 */
class Sharing extends BaseSharing {
	
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
	 * permissions
	 * @ORM\ManyToMany(targetEntity="Permission", inversedBy="sharingEntries")
	 * @ORM\JoinTable(name="son_sharing_permissions",
	 *      joinColumns={@ORM\JoinColumn(name="sharing_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="permission_id", referencedColumnName="id")}
	 * )
	 */
	protected $permissions;
	
	
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
		// setup modified
		if(is_null($this->getLastModified())) {
			$this->setLastModified(new \DateTime());
		}
		
		// setup permissions
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
	 * Set lastModified
	 *
	 * @param \DateTime $lastModified
	 *
	 * @return Sharing
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
