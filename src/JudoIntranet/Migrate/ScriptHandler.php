<?php

/*
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * based on https://github.com/vaniocz/symlink-handler, licensed under MIT, (c) 2016 Vanio Solutions
 * adapted to fit this project migration requirements, i.e. creating symlinks for the old assets
 * to web
 */

namespace JudoIntranet\Migrate;

use Composer\Config;
use Composer\Package\PackageInterface;
use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

class ScriptHandler
{
	
	/**
	 * creates symlinks, requires an object in extras section:
	 * "symlinks" : {
	 * 		"sourceRelativePath": "targetRelativePath",
	 * 		...
	 * }
	 * paths are relative to project root directory
	 * 
	 * @param Event $event
	 * @param Filesystem $filesystem
	 */
	public static function createSymlinks(Event $event, Filesystem $filesystem = null) {
		
		// get package
		/** @var PackageInterface $package */
		$package = $event->getComposer()->getPackage();
        
		// get composer config
		/** @var Config $config */
		$config = $event->getComposer()->getConfig();
		
		// get symlink entries from "extra" section
		$symlinks = (array) $package->getExtra()['symlinks'];
		$symlinks = (isset($symlinks) ? $symlinks : array());
		
		// get vendor and root path
		$vendorPath = $config->get('vendor-dir');
		$rootPath = dirname($vendorPath);
		
		// get new filesystem, if null
		$filesystem = (isset($filesystem) ? $filesystem : new Filesystem);
		
		// walk through symlinks
		foreach ($symlinks as $sourceRelativePath => $targetRelativePath) {
			
			// get absolute source path
			$sourceAbsolutePath = sprintf('%s/%s', $rootPath, $sourceRelativePath);
			if (!file_exists($sourceAbsolutePath)) {
				continue;
			}
			
			// console output
			$event->getIO()->write(sprintf(
				'<info>Creating symlink for "%s" into "%s"</info>',
				$sourceRelativePath,
				$targetRelativePath
			));
			
			// create symlink
			$filesystem->symlink($sourceAbsolutePath, sprintf('%s/%s', $rootPath, $targetRelativePath));
		}
	}
}