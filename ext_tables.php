<?php
if (!defined('TYPO3_MODE')) {
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
        'EXT:datafilter/Resources/Private/Language/locallang_csh_txdatafilterfilters.xlf'
);
