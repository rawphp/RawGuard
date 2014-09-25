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

use RawPHP\RawGuard\Role;

/**
 * Role tests.
 * 
 * @category  PHP
 * @package   RawPHP/RawGuard/Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class RoleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Role
     */
    public $role;
    
    /**
     * Setup before each test.
     */
    public function setUp( )
    {
        parent::setUp( );
        
        $this->role = new Role( );
    }
    
    /**
     * Cleanup after each test.
     */
    public function tearDown( )
    {
        parent::tearDown( );
        
        $this->role = NULL;
    }
    
    /**
     * Test role instantiated correctly.
     */
    public function testRoleInstantiation( )
    {
        $this->assertNotNull( $this->role );
    }
    
    /**
     * Test initialising the role.
     * 
     * @global array $config configuration array
     */
    public function testInitRole( )
    {
        global $config;
        
        $roles = array_values( $config[ 'roles' ] );
        
        $this->role->init( $roles[ 1 ] );
        
        $this->assertEquals( 'admin', $this->role->getName( ) );
        
        $caps = $this->role->getCaps( );
        
        $this->assertEquals( 'add_users', $caps[ 0 ] );
        $this->assertEquals( 'delete_users', $caps[ 1 ] );
    }
}