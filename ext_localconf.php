<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

// Register as Data Provider service
// Note that the subtype corresponds to the name of the database table
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
	'datafilter',
	// Service type
	'datafilter',
	// Service key
	'tx_datafilter',
	array(
		'title' => 'Data Filter',
		'description' => 'Standard Data Filter',

		'subtype' => 'tx_datafilter_filters',

		'available' => TRUE,
		'priority' => 50,
		'quality' => 50,

		'os' => '',
		'exec' => '',

		'className' => \Tesseract\Datafilter\Component\DataFilter::class,
	)
);
