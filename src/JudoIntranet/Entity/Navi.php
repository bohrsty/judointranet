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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;


/**
 * @ORM\Entity
 * @ORM\Table(name="orm_navi")
 * @ORM\HasLifecycleCallbacks
 */
class Navi {
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string", length=75)
	 */
	private $name;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Navi", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
	 */
	private $parent;
	
	/**
	 * @ORM\Column(type="string", length=75, name="file_param", nullable=true, options={"default"=null})
	 */
	private $fileParam;
	
	/**
	 * @ORM\Column(type="string", length=75, nullable=true, options={"default":null})
	 */
	private $url;
	
	/**
	 * @ORM\Column(type="integer", length=3)
	 */
	private $position;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $show;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $valid;
	
	/**
	 * @ORM\Column(type="string", length=1, name="required_permission", options={"default":"r"})
	 */
	private $requiredPermission;
	
	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $icon;
	
	/**
	 * @ORM\Column(type="datetime", name="last_modified")
	 */
	private $lastModified;
	
	/**
	 * @ORM\OneToMany(targetEntity="Navi", mappedBy="parent")
	 */
	private $children;
	
	
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
		// setup modified
		if(is_null($this->getLastModified())) {
			$this->setLastModified(new \DateTime());
		}
		
		// setup children
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
	 * @return Navi
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
	 * Set parent
	 *
	 * @param string $parent
	 *
	 * @return Navi
	 */
	public function setParent($parent) {
		$this->parent = $parent;
		
		return $this;
	}
	
	/**
	 * Get parent
	 *
	 * @return string
	 */
	public function getParent() {
		return $this->parent;
	}
	
	/**
	 * Set fileParam
	 *
	 * @param string $fileParam
	 *
	 * @return Navi
	 */
	public function setFileParam($fileParam) {
		$this->fileParam = $fileParam;
		
		return $this;
	}
	
	/**
	 * Get fileParam
	 *
	 * @return string
	 */
	public function getFileParam() {
		return $this->fileParam;
	}
	
	/**
	 * Set URL
	 *
	 * @param string $url
	 *
	 * @return Navi
	 */
	public function setUrl($url) {
		$this->url = $url;
		
		return $this;
	}
	
	/**
	 * Get URL
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * Set posision
	 *
	 * @param string $position
	 *
	 * @return Navi
	 */
	public function setPosition($position) {
		$this->position = $position;
		
		return $this;
	}
	
	/**
	 * Get position
	 *
	 * @return string
	 */
	public function getPosition() {
		return $this->position;
	}
	
	/**
	 * Set show
	 *
	 * @param boolean $show
	 *
	 * @return Navi
	 */
	public function setShow($show) {
		$this->show = $show;
		
		return $this;
	}
	
	/**
	 * Get show
	 *
	 * @return boolean
	 */
	public function getShow() {
		return $this->show;
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
	 * Set requiredPermission
	 *
	 * @param string $requiredPermission
	 *
	 * @return Navi
	 */
	public function setRequiredPermission($requiredPermission) {
		$this->requiredPermission = $requiredPermission;
		
		return $this;
	}
	
	/**
	 * Get requiredPermission
	 *
	 * @return string
	 */
	public function getRequiredPermission() {
		return $this->requiredPermission;
	}
	
	/**
	 * Set icon
	 *
	 * @param string $icon
	 *
	 * @return Navi
	 */
	public function setIcon($icon) {
		$this->icon = $icon;
		
		return $this;
	}
	
	/**
	 * Get icon
	 *
	 * @return string
	 */
	public function getIcon() {
		return $this->icon;
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
	 * Get children
	 * 
	 * @return ArrayCollection
	 */
	public function getChildren() {
		
		// create criteria
		$criteria = Criteria::create()
			->where(Criteria::expr()->eq('valid', true))
			->andWhere(Criteria::expr()->eq('show', true))
			->orderBy(array('position' => Criteria::ASC));
		
		// match criteria
		return $this->children->matching($criteria);
	}
	
	
	/*
	 * Methods
	 */
	/**
	 * get key for react
	 * 
	 * @return string
	 */
	public function getKey() {
		return str_replace('.', '', $this->name);
	}
	
	
	/**
	 * get router
	 * 
	 * @return bool
	 */
	public function getRouter() {
		return $this->fileParam === null && $this->url !== null;
	}
	
	
	/**
	 * get legacy url and parameter
	 * 
	 * @return string
	 */
	private function getLegacyUrl() {
		
		// split file_param by |
		$urlParts = explode('|', $this->getFileParam());
		
		// check params
		if(!isset($urlParts[1]) || $urlParts[1] == '') {
			return $urlParts[0];
		} else {
			return $urlParts[0].'?id='.$urlParts[1];
		}
	}
	
	
	/**
	 * get complete URL (router or legacy)
	 * 
	 * @return string
	 */
	public function getCompleteUrl() {
		
		if($this->getRouter() === true) {
			return $this->getUrl();
		} else {
			return $this->getLegacyUrl();
		}
	}
	
	
	/**
	 * return this navi object and its children as array accorting to $maxDepth
	 * 
	 * @param int $maxDepth the max depth to return the children
	 * @param int $currentDepth the current depth in the tree of children
	 * @return array 
	 */
	public function getNaviTree(int $maxDepth, int $currentDepth) {
		
		// if reached max depth, subitems remain empty
		$children = array();
		if($currentDepth < $maxDepth) {
			
			// return $this and children as array
			foreach($this->getChildren() as $child) {
				$children[] = $child->getNaviTree($maxDepth, $currentDepth + 1);
			}
		}
		
		// return array
		return array(
			'name' => $this->getName(),
			'url' => $this->getCompleteUrl(),
			'key' => $this->getKey(),
			'icon' => $this->getIcon(),
			'router' => $this->getRouter(),
			'subItems' => $children,
		);
	}
}
