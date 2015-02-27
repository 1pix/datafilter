<?php

/*********************************************************************
* Extension configuration file for ext "datafilter".
*
* Generated by ext 27-02-2015 16:43 UTC
*
* https://github.com/t3elmar/Ext
*********************************************************************/

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
  'version' => '1.8.2',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '4.5.0-6.2.99',
      'tesseract' => '1.5.0-0.0.0',
      'expressions' => '',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => 'a:32:{s:9:"ChangeLog";s:4:"beab";s:10:"README.txt";s:4:"3afe";s:23:"class.tx_datafilter.php";s:4:"4791";s:16:"ext_autoload.php";s:4:"5d11";s:12:"ext_icon.gif";s:4:"bec1";s:17:"ext_localconf.php";s:4:"d3ee";s:14:"ext_tables.php";s:4:"5fc0";s:14:"ext_tables.sql";s:4:"972a";s:37:"locallang_csh_txdatafilterfilters.xml";s:4:"7abd";s:16:"locallang_db.xml";s:4:"6a53";s:7:"tca.php";s:4:"5478";s:26:"Documentation/Includes.txt";s:4:"c83c";s:23:"Documentation/Index.rst";s:4:"ed04";s:26:"Documentation/Settings.yml";s:4:"fe21";s:34:"Documentation/Developers/Index.rst";s:4:"58c9";s:45:"Documentation/Images/DataFilterGeneralTab.png";s:4:"fe46";s:58:"Documentation/Images/DataFilterPaginationConfiguration.png";s:4:"b1c6";s:45:"Documentation/Images/DataFilterSessionTab.png";s:4:"967c";s:36:"Documentation/Introduction/Index.rst";s:4:"78ba";s:37:"Documentation/KnownProblems/Index.rst";s:4:"83ab";s:28:"Documentation/User/Index.rst";s:4:"f743";s:40:"Documentation/User/FilterCache/Index.rst";s:4:"979f";s:48:"Documentation/User/FilterConfiguration/Index.rst";s:4:"aa4e";s:40:"Documentation/User/InputScreen/Index.rst";s:4:"8543";s:35:"Documentation/User/Limits/Index.rst";s:4:"133f";s:50:"Documentation/User/OrderingConfiguration/Index.rst";s:4:"b21c";s:43:"Documentation/User/SessionStorage/Index.rst";s:4:"af75";s:66:"interfaces/interface.tx_datafilter_postprocessEmptyFilterCheck.php";s:4:"9a58";s:56:"interfaces/interface.tx_datafilter_postprocessfilter.php";s:4:"4878";s:35:"res/icons/add_datafilter_wizard.gif";s:4:"8a58";s:40:"res/icons/icon_tx_datafilter_filters.gif";s:4:"dd56";s:42:"tests/tx_datafilter_configuration_Test.php";s:4:"789d";}',
  'user' => 'francois',
  'comment' => 'Corrected bug in error output',
);

?>