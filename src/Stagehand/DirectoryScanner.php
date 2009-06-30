<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP version 5
 *
 * Copyright (c) 2009 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Stagehand_DirectoryScanner
 * @copyright  2009 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @since      File available since Release 1.0.0
 */

// {{{ Stagehand_DirectoryScanner

/**
 * A directory scanner.
 *
 * @package    Stagehand_DirectoryScanner
 * @copyright  2009 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */
class Stagehand_DirectoryScanner
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    private $excludes = array('^CVS$',
                              '^.svn',
                              '^.git',
                              '\.swp$',
                              '~$',
                              '\.bak$',
                              '^#.+#$',
                              '^.#'
                              );
    private $includes = array();
    private $recursivelyScans = false;
    private $callback;

    /**#@-*/

    /**#@+
     * @access public
     */

    // }}}
    // {{{ __construct()

    /**
     * Sets the callback to the properties.
     *
     * @param callback $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    // }}}
    // {{{ scan()

    /**
     * Scans the directory and invoke the callback.
     *
     * @param string $directory
     */
    public function scan($directory)
    {
        $files = scandir($directory);
        for ($i = 0, $count = count($files); $i < $count; ++$i) {
            if ($files[$i] == '.' || $files[$i] == '..') {
                continue;
            }

            foreach ($this->excludes as $exclude) {
                if (preg_match("/$exclude/", $files[$i])) {
                    continue 2;
                }
            }

            if (count($this->includes)) {
                $skips = true;
                foreach ($this->includes as $include) {
                    if (preg_match("/$include/", $files[$i])) {
                        $skips = false;
                        break;
                    }
                }

                if ($skips) {
                    continue;
                }
            }

            $element = $directory . DIRECTORY_SEPARATOR . $files[$i];
            call_user_func($this->callback, $element);

            if (is_dir($element) && $this->isRecursive) {
                $this->scan($element);
            }
        }
    }

    // }}}
    // {{{ recursivelyScans()

    /**
     */
    public function recursivelyScans()
    {
        $this->recursivelyScans = true;
    }

    // }}}
    // {{{ notRecursivelyScans()

    /**
     */
    public function notRecursivelyScans()
    {
        $this->recursivelyScans = false;
    }

    // }}}
    // {{{ addExclude()

    /**
     * @param string $exclude
     */
    public function addExclude($exclude)
    {
        $this->excludes[] = $exclude;
    }

    // }}}
    // {{{ addInclude()

    /**
     * @param string $include
     */
    public function addInclude($include)
    {
        $this->includes[] = $include;
    }

    /**#@-*/

    /**#@+
     * @access protected
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    // }}}
}

// }}}

/*
 * Local Variables:
 * mode: php
 * coding: iso-8859-1
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
