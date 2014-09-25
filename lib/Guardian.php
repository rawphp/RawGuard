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

use RawPHP\RawBase\Component;
use RawPHP\RawBase\Exceptions\RawException;
use RawPHP\RawGuard\IGuardian;
use RawPHP\RawGuard\IUser;

/**
 * Guardian class.
 * 
 * @category  PHP
 * @package   RawPHP/RawGuard
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class Guardian extends Component implements IGuardian
{
    /**
     * @var array
     */
    public $roles               = array();
    
    /**
     * Initialises the guardian.
     * 
     * @param array $config configuration array
     */
    public function init( $config = NULL )
    {
        if ( !is_array( $config ) )
        {
            throw new RawException( '$config must be an array' );
        }
        
        foreach( $config as $key => $value )
        {
            switch( $key )
            {
                case 'debug':
                    $this->debug = $value;
                    break;
                
                case 'roles':
                    if ( !is_array( $value ) )
                    {
                        throw new RawException( 'roles must be an array' );
                    }
                    foreach( $value as $val )
                    {
                        $role = new Role( );
                        $role->init( $val );
                        
                        $this->roles[] = $role;
                    }
                    break;
                
                default:
                    // do nothing
                    break;
            }
        }
    }
    
    /**
     * Returns the current active roles.
     * 
     * @return array list of roles
     */
    public function getRoles( )
    {
        return $this->roles;
    }
    
    /**
     * Checks if a user can perform some action.
     * 
     * @param IUser  $user the user
     * @param string $cap  the capability
     * 
     * @filter ON_USER_CAN_FILTER(3)
     * 
     * @return bool TRUE if allowed, FALSE if not
     */
    public function userCan( IUser $user, $cap )
    {
        $retVal = FALSE;
        
        foreach( $user->getCapabilities( ) as $can )
        {
            if ( $cap === $can )
            {
                $retVal = TRUE;
                
                break;
            }
        }
        
        return $this->filter( self::ON_USER_CAN_FILTER, $retVal, $user, $cap );
    }
    
    const ON_USER_CAN_FILTER                = 'on_user_can_filter';
}