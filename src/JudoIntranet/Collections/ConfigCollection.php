<?php

/*
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet\Collections;


use Doctrine\ORM\EntityManager;

class ConfigCollection {
	
	
	/*
	 * Class variables
	 */
	private $em;
	
	/**
	 * Constructor
	 */
	public function __construct(EntityManager $em) {
		
		// set doctrine
		$this->em = $em;
	}
	
	
	/*
	 * Methods
	 */
	/**
	 * getConfigByName(string $name)
	 * loads the config entity $name from database and returns its value
	 * 
	 * @return string|false
	 */
	public function getConfigByName($name) {
		
		// get repository
		$repositoryConfig = $this->em->getRepository('JudoIntranet:Config');
		// get config entity
		$config = $repositoryConfig->findOneBy(
			array(
					'name' => $name,
					'valid' => true,
			)
		);
		
		// check result
		if(get_class($config) != 'JudoIntranet\\Entity\\Config') {
			return false;
		} else {
			return $config->getValue();
		}
	}
}
