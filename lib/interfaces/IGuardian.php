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
 * @package   RawPHP/RawGuard
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawGuard;

/**
 * Guardian interface.
 * 
 * @category  PHP
 * @package   RawPHP/RawGuard
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
interface IGuardian
{
    /**
     * Initialises the guardian.
     * 
     * @param array $config configuration array
     * 
     * @todo cleanup hierarchical capabilities
     * 
     * @action ON_INIT_ACTION
     */
    public function init( $config = NULL );
    
    /**
     * Returns role by name.
     * 
     * @param string $name role name
     * 
     * @action ON_GET_ROLE_ACTION
     * 
     * @filter ON_GET_ROLE_FILTER(2)
     * 
     * @return Role the role
     */
    public function getRole( $name );
    
    /**
     * Returns the current active roles.
     * 
     * @filter ON_GET_ROLES_FILTER(1)
     * 
     * @return array list of roles
     */
    public function getRoles( );
    
    /**
     * Checks if a user can perform some action.
     * 
     * @param IUser  $user the user
     * @param string $cap  the capability
     * 
     * @action ON_USER_CAN_ACTION
     * 
     * @filter ON_USER_CAN_FILTER(3)
     * 
     * @return bool TRUE if allowed, FALSE if not
     */
    public function userCan( IUser $user, $cap );
    
    /**
     * Returns the pretty name for a role or capability.
     * 
     * @param string $name the role or capability name
     * 
     * @filter ON_GET_PRETTY_NAME_FILTER(2)
     * 
     * @return string prettified name
     */
    public function getPrettyName( $name );
}