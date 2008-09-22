<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_datafilter_filters'] = array (
	'ctrl' => $TCA['tx_datafilter_filters']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,title,configuration,additional_sql'
	),
	'feInterface' => $TCA['tx_datafilter_filters']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'title' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:datafilter/locallang_db.xml:tx_datafilter_filters.title',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required,trim',
			)
		),
		'configuration' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:datafilter/locallang_db.xml:tx_datafilter_filters.configuration',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
		'additional_sql' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:datafilter/locallang_db.xml:tx_datafilter_filters.additional_sql',		
			'config' => array (
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;;;2-2-2, configuration;;;;3-3-3, additional_sql')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>