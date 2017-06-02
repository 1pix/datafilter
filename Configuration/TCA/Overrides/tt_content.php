<?php

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
