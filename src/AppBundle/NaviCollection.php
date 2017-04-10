<?php

/*
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle;


class NaviCollection {
	
	
	/*
	 * Class variables
	 */
	private $doctrine;
	
	/*
	 * Getter/Setter
	 */
	public function getDoctrine() {
		return $this->doctrine;
	}
	
	public function setDoctrine($doctrine) {
		$this->doctrine = $doctrine;
		
		return $this;
	}
	
	/**
	 * Constructor
	 */
	public function __construct($doctrine) {
		
		// set doctrine
		$this->setDoctrine($doctrine);
	}
	
	
	/*
	 * Methods
	 */
	/**
	 * loads navi entries (level 0)
	 * 
	 * @return array
	 */
	public function loadNavi() {
		
		// get all entries level 0 (parent == NULL)
		$repositoryNavi = $this->getDoctrine()->getRepository('AppBundle:Navi');
		$naviEntries = $repositoryNavi->findBy(
			array(
				'parent' => null,
				'valid' => true,
				'show' => true
			),
			array('position' => 'ASC')
		);
		
		// walk through entries and get tree
		$naviTree = array();
		foreach($naviEntries as $entry) {
// TODO: config to orm -> get navi.maxDepth (\Object::staticGetGc()->get_config('navi.maxDepth'))
			// exclude "homepage"
			if($entry->getId() != 1) {
				$naviTree[] = $entry->getNaviTree(2, 1);
			}
		}
		
		// return
		return $naviTree;
	}
}
