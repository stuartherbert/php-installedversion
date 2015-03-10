<?php

/**
 * Copyright (c) 2015-present Stuart Herbert.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package     Stuart
 * @subpackage  MyVersionLib
 * @author      Stuart Herbert <stuart@stuartherbert.com>
 * @copyright   2015-present Stuart Herbert
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://stuartherbert.github.io/php-semver
 */

namespace Stuart;

class MyVersion
{
	/**
	 * the version number that we worked out
	 *
	 * @var null|string
	 */
	protected $version = null;

	/**
	 * constructor. works out our version number
	 *
	 * @param string $myName
	 *        the name of the composer package to get the version of
	 */
	public function __construct($myName)
	{
		$this->version = $this->determinePackageVersion($myName);
	}

	/**
	 * determine the version of a given package by looking in
	 * the installed.json file.
	 *
	 * @param  string $myName
	 * @return string
	 */
	protected function determinePackageVersion($myName)
	{
		// does $myName exist in the composer file?
		$version = $this->determineVersionFromComposer($myName);
		if ($version) {
			// success :)
			return $version;
		}

		// if we get here, then we're installed into a git clone of $myName
		return $this->determineVersionFromGit();
	}

	/**
	 * resort to Git for working out our version number
	 *
	 * @return string
	 */
	protected function determineVersionFromGit()
	{
		$topDir = $this->getTopDir();

		$currentBranch = rtrim(`cd $topDir && git rev-parse --abbrev-ref HEAD`);
		$currentCommit = rtrim(`cd $topDir && git rev-parse HEAD`);

		return "dev-" . $currentBranch . '-' . $currentCommit;
	}

	/**
	 * [determineVersionFromComposer description]
	 * @param  [type] $myName       [description]
	 * @param  [type] $composerFile [description]
	 * @return [type]               [description]
	 */
	protected function determineVersionFromComposer($myName)
	{
		// do we have a 'composer/installed.json' file?
		$composerFile = $this->findComposerFile();
		if (!$composerFile) {
			return false;
		}

		// find our details from the file
		$packages = json_decode(file_get_contents($composerFile));
		foreach ($packages as $package) {
			if ($package->name !== $myName) {
				continue;
			}

			// at this point, we know who we are
			return $package->version;
		}

		// if we get here, we do not know what version we are
		return false;
	}

	/**
	 * where is the root folder for this project?
	 *
	 * this assumes that we are installed into the vendor/ folder of the
	 * project
	 *
	 * @return string
	 */
	protected function getTopDir()
	{
		return realpath(__DIR__ . "/../../../../../");
	}

	/**
	 * locate the 'installed.json' file created by composer
	 *
	 * we use this file because it will exist even if you manually nuke the
	 * composer.lock file
	 *
	 * @return string
	 */
	protected function findComposerFile()
	{
		$composerFile = $this->getTopDir() . "/vendor/composer/installed.json";
		if (!file_exists($composerFile)) {
			return false;
		}

		return $composerFile;
	}

	/**
	 * return our version number
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->version;
	}
}