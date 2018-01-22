<?php

/*
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet\Legacy;



/**
 * provides some legacy function for Navi entity
 */
class Navi extends \Object {
    
    
    public static function idFromFileParam($file, $id) {
        
        // get container
        $container = self::staticGetContainer();
        // get entity manager
        $em = $container->get('doctrine.orm.entity_manager');
        // get repository
        $naviRepository = $em->getRepository('JudoIntranet:Navi');
        
        // find entity
        $navi = $naviRepository->findOneByFileParam($file.'|'.$id);
        
        // check entity
        if(is_null($navi)) {
            throw new \Exception('Navi entity for "'.$file.'|'.$id.'" not found"');
        }
        
        // return id
        return $navi->getId();
    }
}