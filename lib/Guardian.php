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
    public $roles               = array( );
    
    public $useHierarchy        = FALSE;
    
    /**
     * Initialises the guardian.
     * 
     * @param array $config configuration array
     * 
     * @todo cleanup hierarchical capabilities
     * 
     * @action ON_INIT_ACTION
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
                
                case 'use_hierarchy':
                    $this->useHierarchy = ( bool )$value;
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
                        
                        $this->roles[ ] = $role;
                    }
                    break;
                
                default:
                    // do nothing
                    break;
            }
        }
        
        if ( $this->useHierarchy )
        {
            $lastIndex = count( $this->roles ) - 1;
            
            $i = $lastIndex;
            
            while ( $i > 0 )
            {
                foreach( $this->roles[ $i ]->capabilities as $cap )
                {
                    $j = 0;
                    
                    while ( $j < $i )
                    {
                        if ( !in_array( $cap, $this->roles[ $j ]->capabilities ) )
                        {
                            $this->roles[ $j ]->capabilities[ ] = $cap;
                        }
                        
                        $j++;
                    }
                }
                
                $i--;
            }
        }
        
        $this->doAction( self::ON_INIT_ACTION );
    }
    
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
    public function getRole( $name )
    {
        $retVal = NULL;
        
        foreach( $this->roles as $role )
        {
            if ( $name === $role->name )
            {
                $retVal = $role;
                
                break;
            }
        }
        
        $this->doAction( self::ON_GET_ROLE_ACTION );
        
        return $this->filter( self::ON_GET_ROLE_FILTER, $retVal, $name );
    }
    
    /**
     * Returns the current active roles.
     * 
     * @filter ON_GET_ROLES_FILTER(1)
     * 
     * @return array list of roles
     */
    public function getRoles( )
    {
        return $this->filter( self::ON_GET_ROLES_FILTER, $this->roles );
    }
    
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
        
        $this->doAction( self::ON_USER_CAN_ACTION );
        
        return $this->filter( self::ON_USER_CAN_FILTER, $retVal, $user, $cap );
    }
    
    /**
     * Returns the pretty name for a role or capability.
     * 
     * @param string $name the role or capability name
     * 
     * @filter ON_GET_PRETTY_NAME_FILTER(2)
     * 
     * @return string prettified name
     */
    public function getPrettyName( $name )
    {
        $retVal = '';
        
        $tokens = explode( '_', $name );
        
        foreach( $tokens as $token )
        {
            $token = strtoupper( substr( $token, 0, 1 ) ) . substr( $token, 1 );
            
            $retVal .= $token . ' ';
        }
        
        $retVal = trim( $retVal );
        
        return $this->filter( self::ON_GET_PRETTY_NAME_FILTER, $retVal, $name );
    }
    
    // actions
    const ON_INIT_ACTION                    = 'on_init_action';
    const ON_GET_ROLE_ACTION                = 'on_get_role_action';
    const ON_USER_CAN_ACTION                = 'on_user_can_action';
    
    // filters
    const ON_GET_ROLE_FILTER                = 'on_get_role_filter';
    const ON_GET_ROLES_FILTER               = 'on_get_roles_filter';
    const ON_USER_CAN_FILTER                = 'on_user_can_filter';
    const ON_GET_PRETTY_NAME_FILTER         = 'on_get_pretty_name_filter';
}