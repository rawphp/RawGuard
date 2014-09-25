<?php

/**
 * This file is part of RawPHP - a PHP Framework.
 * 
 * Copyright (c) 2014 RawPHP.org
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 * PHP version 5.3
 * 
 * @category  PHP
 * @package   RawPHP/RawGuard/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawGuard\Tests;

use RawPHP\RawGuard\Guardian;
use RawPHP\RawGuard\Tests\User;

/**
 * Guardian tests.
 * 
 * @category  PHP
 * @package   RawPHP/RawGuard/Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class GuardianTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Guardian
     */
    public $guard;
    
    /**
     * Setup before each test.
     */
    public function setUp( )
    {
        parent::setUp( );
        
        $this->guard = new Guardian( );
    }
    
    /**
     * Cleanup after each test.
     */
    public function tearDown( )
    {
        parent::tearDown( );
        
        $this->guard = NULL;
    }
    
    /**
     * Test guardian instantiated correctly.
     */
    public function testRoleInstantiation( )
    {
        $this->assertNotNull( $this->guard );
    }
    
    /**
     * Test initialising the role.
     * 
     * @global array $config configuration array
     */
    public function testInitRole( )
    {
        global $config;
        
        $this->guard->init( $config );
        
        $roles = $this->guard->getRoles( );
        
        $this->assertEquals( 'super_admin', $roles[ 0 ]->getName( ) );
        $this->assertEquals( 'admin', $roles[ 1 ]->getName( ) );
        $this->assertEquals( 'normal', $roles[ 2 ]->getName( ) );
        $this->assertEquals( 'visitor', $roles[ 3 ]->getName( ) );
    }
    
    /**
     * Test initialising hierarchical capabilities.
     * 
     * @global array $config configuration array
     */
    public function testHierarchySetup( )
    {
        global $config;
        
        $this->guard->init( $config );
        
        $roles = $this->guard->getRoles( );
        
        $visitorRole = $roles[ 3 ];
        $this->assertEquals( 1, count( $visitorRole->getCaps( ) ) );
        
        $normalRole = $roles[ 2 ];
        $this->assertEquals( 2, count( $normalRole->getCaps( ) ) );
        
        $adminRole = $roles[ 1 ];
        $this->assertEquals( 4, count( $adminRole->getCaps( ) ) );
        
        $superAdminRole = $roles[ 0 ];
        $this->assertEquals( 6, count( $superAdminRole->getCaps( ) ) );
    }
    
    /**
     * Test if user can add new users.
     */
    public function testUserCanAddUsers( )
    {   
        $user = new User();
        $user->roles[ ] = $this->_getRole( 'administrator' );
        
        //$this->assertFalse( $this->guard->userCan( $user, $cap))
    }
    
    /**
     * Helper method to get a test role.
     * 
     * @param string $name role name
     * 
     * @global array $config configuration array
     * 
     * @return Role a role instance
     */
    private function _getRole( $name = 'administrator' )
    {
        global $config;
        
        return NULL;
    }
}