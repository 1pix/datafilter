<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "datafilter".
 *
 * Auto generated 06-06-2017 16:55
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'Standard Data Filter - Tesseract project',
  'description' => 'Provides a way to create complex filters that can be passed to a Data Provider for restricting the data it returns. More info on http://www.typo3-tesseract.com/',
  'category' => 'fe',
  'author' => 'Francois Suter (Cobweb)',
  'author_email' => 'typo3@cobweb.ch',
  'state' => 'stable',
  'uploadfolder' => 0,
  'createDirs' => '',
  'clearCacheOnLoad' => 0,
  'author_company' => '',
  'version' => '2.1.0',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '7.6.0-8.99.99',
      'tesseract' => '2.0.0-0.0.0',
      'expressions' => '2.0.0-0.0.0',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:34:{s:9:"ChangeLog";s:4:"a962";s:11:"LICENSE.txt";s:4:"6404";s:9:"README.md";s:4:"beb7";s:13:"composer.json";s:4:"700b";s:12:"ext_icon.png";s:4:"bffb";s:17:"ext_localconf.php";s:4:"e2d1";s:14:"ext_tables.php";s:4:"d3a9";s:14:"ext_tables.sql";s:4:"82a5";s:48:"Classes/PostprocessEmptyFilterCheckInterface.php";s:4:"2b47";s:38:"Classes/PostprocessFilterInterface.php";s:4:"38f3";s:32:"Classes/Component/DataFilter.php";s:4:"c28e";s:43:"Configuration/TCA/tx_datafilter_filters.php";s:4:"c1d0";s:42:"Configuration/TCA/Overrides/tt_content.php";s:4:"1497";s:26:"Documentation/Includes.txt";s:4:"c83c";s:23:"Documentation/Index.rst";s:4:"9356";s:26:"Documentation/Settings.yml";s:4:"9f73";s:34:"Documentation/Developers/Index.rst";s:4:"9711";s:45:"Documentation/Images/DataFilterGeneralTab.png";s:4:"77aa";s:58:"Documentation/Images/DataFilterPaginationConfiguration.png";s:4:"9b27";s:45:"Documentation/Images/DataFilterSessionTab.png";s:4:"637f";s:36:"Documentation/Introduction/Index.rst";s:4:"38ee";s:37:"Documentation/KnownProblems/Index.rst";s:4:"83ab";s:28:"Documentation/User/Index.rst";s:4:"f743";s:40:"Documentation/User/FilterCache/Index.rst";s:4:"979f";s:48:"Documentation/User/FilterConfiguration/Index.rst";s:4:"aa4e";s:40:"Documentation/User/InputScreen/Index.rst";s:4:"8543";s:35:"Documentation/User/Limits/Index.rst";s:4:"133f";s:50:"Documentation/User/OrderingConfiguration/Index.rst";s:4:"780d";s:43:"Documentation/User/SessionStorage/Index.rst";s:4:"af75";s:64:"Resources/Private/Language/locallang_csh_txdatafilterfilters.xlf";s:4:"97cf";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"077d";s:46:"Resources/Public/Icons/AddDataFilterWizard.png";s:4:"fead";s:37:"Resources/Public/Icons/DataFilter.png";s:4:"411e";s:39:"Tests/Unit/ConfigurationParsingTest.php";s:4:"035d";}',
);

