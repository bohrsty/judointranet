<?php
/* ********************************************************************************************
 * Copyright (c) 2011 Nils Bohrs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
 * to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 * 
 * Thirdparty licenses see LICENSE
 * 
 * ********************************************************************************************/

class UserTest extends PHPUnit_Framework_TestCase {
	
	// variables
	private $user;
	
	// setup
	public function setUp() {
		
		// create user
		$this->user = new User();
	}
	
	
	public function tearDown() {
		
	}
	
	
	public function testUserSetterGetter() {
		
		// id
		$data = 1;
		
		$this->user->set_id($data);
		$this->assertEquals($data, $this->user->get_id());
		
		// groups
		$data = array(new Group(1));
		
		$this->user->set_groups($data);
		$testGroups = $this->user->get_groups();
		$this->assertEquals($data[0]->getId(), $testGroups[0]->getId());
		
		// loggedin
		$data = true;
		
		$this->user->set_loggedin($data);
		$this->assertEquals($data, $this->user->get_loggedin());
		
		// lang
		$data = 'de_DE';
		
		$this->user->set_lang($data);
		$this->assertEquals($data, $this->user->get_lang());
		
		$changeLang = 'en_US';
		$this->user->change_lang($changeLang);
		$this->assertEquals($changeLang, $this->user->get_lang());
		
		// login message
		$data = 'loginmessage';
		
		$this->user->set_login_message($data);
		$this->assertEquals($data, $this->user->get_login_message());
		
		// userinfo
		$data = array('name' => 'some', 'username' => 'thing');
		$id = 0;
		
		$this->user->set_userinfo($data);
		$this->assertEquals($data, $this->user->get_userinfo());
		$this->assertEquals($data['name'], $this->user->get_userinfo('name'));
		$this->assertEquals($data['username'], $this->user->get_userinfo('username'));
		$this->user->set_id($id);
		$this->assertEquals($id, $this->user->get_userinfo('id'));
		
		$data = array('name' => 'Public', 'username' => 'public');
		$this->user->set_userinfo('name', $data['name']);
		$this->user->set_userinfo('username', $data['username']);
		$this->assertEquals($data['name'], $this->user->get_userinfo('name'));
		$this->assertEquals($data['username'], $this->user->get_userinfo('username'));
		
		// used
		$data = true;
		
		$this->user->setUsed($data);
		$this->assertEquals($data, $this->user->getUsed());
		
		// test existance
		$this->assertTrue(User::exists(1));
		$this->assertFalse(User::exists(-1));
	}
	
	
	
	public function testUserLoginOut() {
		
		// get view
		$view = new	PageView();
		
		// change user to admin
		$this->user->change_user('admin', true);
		$this->assertEquals(1, $this->user->get_id());
		$this->assertEquals(true, $this->user->get_loggedin());
		$this->assertEquals('Administrator', $this->user->get_userinfo('name'));
		$this->assertEquals('admin', $this->user->get_userinfo('username'));
		$this->assertArrayHasKey(1, $this->user->allGroups());
		$tempGroups = $this->user->get_groups();
		$this->assertInstanceOf('Group', $tempGroups[0]);
		
		// logout user
		$logout = $this->user->logout($view);
		// user values
		$this->assertEquals(0, $this->user->userid());
		$this->assertEquals(array(), $this->user->groups());
		$this->assertEquals(false, $this->user->get_loggedin());
		$this->assertEquals('please log on', $this->user->get_login_message());
		$this->assertEquals(
				array(
						'name' => 'Public',
						'username' => 'public',
						'password' => '',
						'email' => '',
						'active' => 1,
						'last_modified' => '',
					),
				$this->user->get_userinfo());
		// session
		$this->assertCount(1, $_SESSION);
		// config
		$this->assertEquals(new Config(), $this->user->getGc());
		// output
		$this->assertContains(_l('logout'), $logout);
		$this->assertContains(_l('logout successful'), $logout);
		$this->assertNotContains('<form ', $logout);
		
		// check login (if default password, else skip)
		$password = TestDb::singleValue('
			SELECT `password`
			FROM `user`
			WHERE `id`=1
		');
		if($password == '21232f297a57a5a743894a0e4a801fc3') {
			
			// (correct credentials)
			$login = $this->user->checkLogin('admin', 'admin');
			$this->assertTrue($login);
			// wrong password
			$login = $this->user->checkLogin('admin', 'wrongPassword');
			$this->assertFalse($login);
		} else {
			$this->markTestSkipped();
		}
		$this->assertEquals('wrong password', $this->user->get_login_message());
		// not existing user
		$login = $this->user->checkLogin('notExistingUser', 'password');
		$this->assertFalse($login);
		$this->assertEquals('user not exist', $this->user->get_login_message());
	}
	
	
	public function testAllGroupsAllUsers() {
		
		// all user
		$admin = new User();
		$admin->change_user('admin', false);
		$allUser = $this->user->return_all_users();
		$this->assertContainsOnlyInstancesOf('User', $allUser);
		$checkUser = null;
		foreach($allUser as $user) {
			if($user->get_id() == 1){
				$checkUser = $user;
				break;
			}
		}
		$this->assertEquals($admin, $checkUser);
		// exclude admin
		$excludeUser = $this->user->return_all_users(array('admin'));
		foreach($excludeUser as $user) {
			if($user->get_id() == 1){
				$this->fail('Found user "admin" in "User::return_all_users(array(\'admin\'))"');
			}
		}
		
		// is used
		$this->assertTrue(User::isUsed(1));
	}
	
	
	public function testPermissionsOnIds() {
		
		// reset user
		$this->user = new User();
		// test public reading
		$this->assertFalse($this->user->hasPermission('calendar', -2));
		$this->assertTrue($this->user->hasPermission('calendar', 1));
		$this->user->change_user('admin', false);
		// test reading (user admin)
		$this->assertTrue($this->user->hasPermission('calendar', 1));
	}
	
	
	public function testGetAllPermittedItems() {
		
		// reset global user
		$this->user = new User();
		$this->user->change_user('admin', true);
		// all items time independent
		$permittedIds = $this->user->permittedItems('calendar', 'r');
		$this->assertInternalType('array', $permittedIds);
		$this->assertGreaterThanOrEqual(1, count($permittedIds));
		// all items im time range
		$permittedIds = $this->user->permittedItems('calendar', 'r', date('Y-m-d', 0), date('Y-m-d'));
		$this->assertInternalType('array', $permittedIds);
		$this->assertGreaterThanOrEqual(1, count($permittedIds));
	}
	
	
	public function testLocalUser() {
		
		// reset global user
		$this->user = new User();
		$this->user->change_user('admin', true);
		// create local user
		$localUser = new User(false);
		// test user
		$this->assertNotNull($_SESSION['user']);
		
	}
	
	
	public function testMembership() {
		
		// reset global user
		$this->user = new User();
		$this->user->change_user('admin', true);
		// admin needs to be member of admin group
		$this->assertTrue($this->user->isMemberOf(1));
		// is admin?
		$this->assertTrue($this->user->isAdmin());
	}
	
	
	public function testWriteBackToDb() {
		
		// reset global user
		$this->user = new User();
		$this->user->change_user('admin', true);
		// create new userinfo
		$userInfo = array(
				'name' => 'Admin Changed',
				'email' => 'changed@localhost',
			);
		$this->user->set_userinfo($userInfo);
		$this->user->writeDb();
		
		// reset global user
		$this->user = new User();
		$this->user->change_user('admin', true);
		$userInfoDB = $this->user->get_userinfo();
		$this->assertEquals($userInfo['name'], $userInfoDB['name']);
		$this->assertEquals($userInfo['email'], $userInfoDB['email']);
		
		// reset userinfo
		$userInfo = array(
				'name' => 'Administrator',
				'email' => 'root@localhost',
			);
		$this->user->set_userinfo($userInfo);
		$this->user->writeDb();
		
		// new user
		$this->user = new User(false);
		$data = array(
				'username' => 'test',
				'password' => md5('test'),
				'name' => 'Testuser',
				'email' => 'test@localhost.local',
				'active' => 1,
				'last_modified' => '',
			);
		$this->user->set_userinfo($data);
		$newGroup = new Group(1);
		$this->user->set_groups(array($newGroup));
		$id = $this->user->writeDb(DB_WRITE_NEW);
		// reset user
		$this->user = new User(false);
		$this->user->change_user($id, false, 'id');
		$this->assertEquals($data['username'], $this->user->get_userinfo('username'));
		$this->assertEquals($data['password'], $this->user->get_userinfo('password'));
		$this->assertEquals($data['name'], $this->user->get_userinfo('name'));
		$this->assertEquals($data['email'], $this->user->get_userinfo('email'));
		$this->assertEquals($data['active'], $this->user->get_userinfo('active'));
		$this->assertEquals($newGroup, $this->user->get_groups()[0]);
		$this->user->delete();
		$this->assertFalse(User::exists($id));
	}
}



?>
