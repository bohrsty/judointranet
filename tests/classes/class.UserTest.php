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
		$data = array('name' => 'Public', 'username' => 'public');
		$id = 0;
		
		$this->user->set_userinfo($data);
		$this->assertEquals($data, $this->user->get_userinfo());
		$this->assertEquals($data['name'], $this->user->get_userinfo('name'));
		$this->assertEquals($data['username'], $this->user->get_userinfo('username'));
		$this->user->set_id($id);
		$this->assertEquals($id, $this->user->get_userinfo('id'));
	}
	
	
	
	public function testUserLoginOut() {
		
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
		$logout = $this->user->logout();
		// user values
		$this->assertEquals(0, $this->user->userid());
		$this->assertEquals(array(), $this->user->groups());
		$this->assertEquals(false, $this->user->get_loggedin());
		$this->assertEquals('class.User#login#message#default', $this->user->get_login_message());
		$this->assertEquals(array(), $this->user->get_userinfo());
		// session
		$this->assertCount(1, $_SESSION);
		// config
		$this->assertEquals(new Config(), $this->user->getGc());
		// output
		$this->assertContains(TestObject::lang('class.User#logout#logout#caption'), $logout);
		$this->assertContains(TestObject::lang('class.User#logout#logout#message'), $logout);
		$this->assertNotContains('<form ', $logout);
		
		// check login
		$login = $this->user->check_login('admin');
		$this->assertArrayHasKey('password', $login);
		$this->assertArrayHasKey('active', $login);
		$login = $this->user->check_login('notExistingUser');
		$this->assertEquals(false, $login);
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
}



?>
