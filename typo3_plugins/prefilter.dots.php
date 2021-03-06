<?php

/***************************************************************
 *  Copyright notice
 *
 *
 *    Created by Simon Tuck <stu@rtpartner.ch>
 *    Copyright (c) 2006-2007, Rueegg Tuck Partner GmbH (wwww.rtpartner.ch)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 *
 *    @copyright     2006, 2007 Rueegg Tuck Partner GmbH
 *    @author     Simon Tuck <stu@rtpartner.ch>
 *    @link         http://www.rtpartner.ch/
 *    @package     Smarty (smarty)
 *
 ***************************************************************/

/**
 *
 * Smarty prefilter "dots"
 * -------------------------------------------------------------
 * File:    prefilter.dots.php
 * Type:    prefilter
 * Name:    Dots
 * Version: 2.0
 * Author:  Simon Tuck <stu@rtpartner.ch>, Rueegg Tuck Partner GmbH
 * Purpose:    regex any typoscript properties inside of Smarty tags so that Smarty will not attempt to parse them
 * Example:    {$myTag typoscript.property.stdWrap.case="upper"} becomes {$myTag typoscript_DOT_property_DOT_stdWrap_DOT_case="upper"}
 * -------------------------------------------------------------
 *
 **/


    function smarty_prefilter_dots($tplSource, &$template)
    {

        // Gets the template delimiters to use in the regex pattern
        $lDel = preg_quote($template->left_delimiter, '%');
        $rDel = preg_quote($template->right_delimiter, '%');

        // Regex pattern matches dot notations inside delimiters
        $tsPattern = '%(\s+\b(?<!$)([\w]+[.]{1}[\w]+)+\s*?=)(?=[^' . $rDel . '|' . $lDel . ']*?' . $rDel . ')%';
        if (preg_match_all($tsPattern, $tplSource, $tsParams)) {

            // Gets the string which will be used to replace the dots in typoscript notations
            $templateId = '___' . $template->_smarty_md5 . '___';

            // Replaces all the dots in the typoscript notation with a unique id.
            $tsParamsModified = str_replace('.', $templateId, $tsParams[1]);

            // Replaces the typoscript notations in the template with their
            // modified versions which have dots replaced.
            $tplSource = str_replace($tsParams[1], $tsParamsModified, $tplSource);
        }

        // Return the tpl_source to the compiler
        return $tplSource;
    }