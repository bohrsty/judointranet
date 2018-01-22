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

class NaviCollection {
	
	
	/*
	 * Class variables
	 */
	private $em;
	private $gc;
	
	/**
	 * Constructor
	 */
	public function __construct(EntityManager $em, ConfigCollection $gc) {
		
		// set em
		$this->em = $em;
		
		// set config
		$this->gc = $gc;
	}
	
	
	/*
	 * Methods
	 */
	/**
	 * loads navi entries (level 0)
	 *
     * @param bool $isLegacy determines if navi tree should be returned in legacy style (default: false)
	 * @return array
	 */
	public function loadNavi($isLegacy = false) {
		
		// get all entries level 0 (parent == NULL)
		$repositoryNavi = $this->em->getRepository('JudoIntranet:Navi');
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
			// exclude "homepage"
			if($entry->getId() != 1 || $isLegacy === true) {
				$naviTree[] = $entry->getNaviTree($this->gc->getConfigByName('navi.maxDepth'), 1, $isLegacy);
			}
		}
		
		// return
		return $naviTree;
	}
}
