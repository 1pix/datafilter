<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_datafilter_filters');

// Register sprite icon for datafilter table
/** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
        'tx_datafilter-datafilter',
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        [
            'source' => 'EXT:datafilter/Resources/Public/Icons/DataFilter.png'
        ]
);

// Add context sensitive help (csh) for this table
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_datafilter_filters',
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY, 'locallang_csh_txdatafilterfilters.xml')
);

// Register datafilter as a Data Filter
$GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter']['config']['allowed'] .= ',tx_datafilter_filters';
$GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter2']['config']['allowed'] .= ',tx_datafilter_filters';

// Add a wizard for adding a datafilter
$addDatafilteryWizard = array(
	'type' => 'script',
	'title' => 'LLL:EXT:datafilter/locallang_db.xml:wizards.add_datafilter',
	'module' => array(
		'name' => 'wizard_add'
	),
	'icon' => 'EXT:datafilter/Resources/Public/Icons/AddDataFilterWizard.png',
	'params' => array(
		'table' => 'tx_datafilter_filters',
		'pid' => '###CURRENT_PID###',
		'setValue' => 'append'
	)
);
$GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter']['config']['wizards']['add_datafilter'] = $addDatafilteryWizard;
$GLOBALS['TCA']['tt_content']['columns']['tx_displaycontroller_datafilter2']['config']['wizards']['add_datafilter2'] = $addDatafilteryWizard;
