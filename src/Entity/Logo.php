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
	
	// non persisted
	/**
	 * @deprecated to be removed if API migrated to symfony
	 */
	private $apiResult;
	
	

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
	 * @param FileType $fileType
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
	 * @return FileType
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
	 * Get api result
	 * 
	 * @deprecated to be removed if API migrated to symfony
	 * @return array
	 */
	public function getApiResult() {
		return $this->apiResult;
	}
	
	/**
	 * Set api result
	 * 
	 * @deprecated to be removed if API migrated to symfony
	 * @param array
	 */
	public function setApiResult($apiResult) {
		$this->apiResult = $apiResult;
	}
	
	
	
	
	
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
		// setup modified
		if(is_null($this->getLastModified())) {
			$this->setLastModified(new \DateTime());
		}
		
		// set api result
		$this->setApiResult(array());
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
		
		return 'data:'.$this->getFileType()->getMimeType().';base64,'.base64_encode(stream_get_contents($this->getData()));
	}
	
	
	/**
	 * factory() creates an object from the given data in $_FILES and $_POST and persists
	 * it in the database. It returns the object
	 * 
	 * @param object $doctrine the dotrine entity manager
	 * @return object the Logo object
	 */
	public static function factory($doctrine) {
		
		// create object
		$logo = new Logo();
		
		// prepare data
		$tempName = $_FILES['file']['tmp_name'];
		$origName = \Object::staticReplaceUmlaute($_FILES['file']['name']);
		// get file type
		$mimeType = mime_content_type($tempName);
		$repositoryFileType = $doctrine->getRepository('AppBundle:FileType');
		$fileType = $repositoryFileType->findOneByMimeType($mimeType);
		// get user
		$legacyUser = \Object::staticGetUser();
		$repositoryUser = $doctrine->getRepository('AppBundle:User');
		$user = $repositoryUser->findOneById($legacyUser->getId());
		
		// check and take file
		$data = null;
		if(is_uploaded_file($tempName) === true) {
			$data = @file_get_contents($tempName);
		} else {
			
			// set error
			$logo->setApiResult(
				array(
						'result' => 'ERROR',
						'message' => _l('ERROR').': '._l('no uploaded file to process'),
					)
			);
			return $logo;
		}
		// check moving uploaded file
		if(is_null($data) || $data === false) {
			
			// set error
			$logo->setApiResult(
				array(
						'result' => 'ERROR',
						'message' => _l('ERROR').': '._l('file processing failed'),
					)
			);
			return $logo;
		}
		
		// persist object
		$logo
			->setName($origName)
			->setFileType($fileType)
			->setData($data)
			->setModifiedBy($user)
			->setValid(true);
		$em = $doctrine->getManager();
		$em->persist($logo);
		$em->flush();
		
		// set error
		$logo->setApiResult(
			array(
					'result' => 'OK',
					'message' => _l('File saved successfully'),
					'data' => array(
							'id' => $logo->getId(),
							'name' => $logo->getName(),
							'src' => 'data:'.$logo->getFileType()->getMimeType().';base64,'.base64_encode($logo->getData()),
						),
				)
		);
		
		// return
		return $logo;
	}
}
