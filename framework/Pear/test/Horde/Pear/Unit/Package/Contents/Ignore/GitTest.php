<?php
/**
 * Copyright 2011-2017 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @category   Horde
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package    Pear
 * @subpackage UnitTests
 */

/**
 * Test the .gitignore handler for package contents.
 *
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @category   Horde
 * @copyright  2011-2017 Horde LLC
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package    Pear
 * @subpackage UnitTests
 */
class Horde_Pear_Unit_Package_Contents_Ignore_GitTest
extends Horde_Pear_TestCase
{
    public function testCreation()
    {
        $a = new Horde_Pear_Package_Contents_Ignore_Git('', '');
    }

    public function testEmpty()
    {
        $this->_checkNotIgnored(
            '/TEST/TEST',
            ''
        );
    }

    public function testMatch()
    {
        $this->_checkIgnored(
            '/TEST/TEST',
            'TEST'
        );
    }

    public function testIgnoreConf()
    {
        $this->_checkIgnored(
            '/TEST/APP/config/conf.php',
            '*/config/conf.php'
        );
    }

    public function testIgnoreConfd()
    {
        $this->_checkIgnored(
            '/TEST/APP/config/conf.d/test.php',
            '*/config/conf.d/*.php'
        );
    }

    public function testSpecificInvalidation()
    {
        $this->_checkNotIgnored(
            '/TEST/APP/config/conf.d/test.php',
            '*/config/conf.d/*.php
!/APP/config/conf.d/test.php'
        );
    }

    public function testComment()
    {
        $this->assertEquals(
            array(),
            $this->_getIgnore('# COMMENT')->getIncludes()
        );
    }

    public function testIgnore()
    {
        $this->assertEquals(
            array('.*[^\/]*\/config\/conf\.d\/[^\/]*\.php$'),
            $this->_getIgnore('*/config/conf.d/*.php')->getIgnores()
        );
    }

    public function testInclude()
    {
        $this->assertEquals(
            array('^\/APP\/[^\/]*$'),
            $this->_getIgnore('!/APP/*')->getIncludes()
        );
    }

    private function _checkIgnored($file, $gitignore)
    {
        $this->assertTrue(
            $this->_getIgnore($gitignore)->isIgnored(new SplFileInfo($file))
        );
    }

    private function _checkNotIgnored($file, $gitignore)
    {
        $this->assertFalse(
            $this->_getIgnore($gitignore)->isIgnored(new SplFileInfo($file))
        );
    }

    private function _getIgnore($gitignore)
    {
        return new Horde_Pear_Package_Contents_Ignore_Git($gitignore, '/TEST');
    }
}
