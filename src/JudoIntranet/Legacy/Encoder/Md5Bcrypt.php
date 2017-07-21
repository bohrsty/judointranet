<?php

/*
 * This file is part of the JudoIntranet package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JudoIntranet\Legacy\Encoder;


use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * uses bcrypt to hash a md5 encoded password
 */
class Md5Bcrypt implements PasswordEncoderInterface {
	
	
	public function encodePassword($password, $salt) {
		
		return password_hash(md5($password), PASSWORD_BCRYPT);
	}
	
	public function isPasswordValid($hash, $password, $salt) {
		
		return password_verify(md5($password), $hash);
	}
}