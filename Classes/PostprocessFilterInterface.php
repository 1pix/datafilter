<?php
namespace Tesseract\Datafilter;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use Tesseract\Datafilter\Component\DataFilter;

/**
 * Interface which defines the method to implement when creating a hook to post-process a filter
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_datafilter
 */
interface PostprocessFilterInterface {
	/**
	 * This method must be implemented for post-processing a filter
	 * It receives a reference to the complete filter object
	 *
	 * @param DataFilter $filter The filter object
	 * @return void
	 */
	public function postprocessFilter(DataFilter $filter);
}
