<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

return array(
        'ctrl' => array(
                'title' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters',
                'label' => 'title',
                'descriptionColumn' => 'description',
                'tstamp' => 'tstamp',
                'crdate' => 'crdate',
                'cruser_id' => 'cruser_id',
                'default_sortby' => 'ORDER BY title',
                'delete' => 'deleted',
                'enablecolumns' => array(
                        'disabled' => 'hidden',
                ),
                'searchFields' => 'title,configuration,orderby',
                'dividers2tabs' => true,
                'typeicon_classes' => array(
                        'default' => 'tx_datafilter-datafilter'
                ),
        ),
        'interface' => array(
                'showRecordFieldList' => 'hidden,title,configuration'
        ),
        'columns' => array(
                'hidden' => array(
                        'exclude' => 1,
                        'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
                        'config' => array(
                                'type' => 'check',
                                'default' => '0'
                        )
                ),
                'title' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.title',
                        'config' => array(
                                'type' => 'input',
                                'size' => '30',
                                'eval' => 'required,trim',
                        )
                ),
                'description' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.description',
                        'config' => array(
                                'type' => 'text',
                                'cols' => '30',
                                'rows' => '4',
                        )
                ),
                'configuration' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.configuration',
                        'config' => array(
                                'type' => 'text',
                                'cols' => '30',
                                'rows' => '6',
                        )
                ),
                'logical_operator' => array(
                        'exclude' => 1,
                        'label' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.logical_operator',
                        'config' => array(
                                'type' => 'radio',
                                'default' => 'AND',
                                'items' => array(
                                        array(
                                                'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.logical_operator.I.0',
                                                'AND'
                                        ),
                                        array(
                                                'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.logical_operator.I.1',
                                                'OR'
                                        ),
                                ),
                        )
                ),
                'orderby' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.orderby',
                        'config' => array(
                                'type' => 'text',
                                'cols' => '30',
                                'rows' => '3',
                        )
                ),
                'limit_start' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.limit_start',
                        'config' => array(
                                'type' => 'input',
                                'size' => '30',
                                'eval' => 'trim',
                        )
                ),
                'limit_offset' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.limit_offset',
                        'config' => array(
                                'type' => 'input',
                                'size' => '30',
                                'eval' => 'trim',
                        )
                ),
                'limit_pointer' => array(
                        'exclude' => 0,
                        'label' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.limit_pointer',
                        'config' => array(
                                'type' => 'input',
                                'size' => '30',
                                'eval' => 'trim',
                        )
                ),
                'session_key' => array(
                        'exclude' => 1,
                        'label' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.session_key',
                        'config' => array(
                                'type' => 'input',
                                'size' => '20',
                                'max' => '50',
                                'eval' => 'trim'
                        )
                ),
                'key_per_page' => array(
                        'exclude' => 1,
                        'label' => 'LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.key_per_page',
                        'config' => array(
                                'type' => 'check',
                                'default' => '0'
                        )
                ),
        ),
        'types' => array(
                '0' => array('showitem' => 'hidden, title;;4, configuration;;1, orderby, --palette--;LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.pagination;2,--div--;LLL:EXT:datafilter/Resources/Private/Language/locallang_db.xlf:tx_datafilter_filters.session, session_key;;3')
        ),
        'palettes' => array(
                '1' => array('showitem' => 'logical_operator'),
                '2' => array(
                        'showitem' => 'limit_start,--linebreak--,limit_offset,--linebreak--,limit_pointer',
                        'canNotCollapse' => 1
                ),
                '3' => array('showitem' => 'key_per_page'),
                '4' => array('showitem' => 'description')
        )
);
