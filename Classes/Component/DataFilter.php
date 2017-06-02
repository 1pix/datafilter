<?php
namespace Tesseract\Datafilter\Component;

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

use Cobweb\Expressions\ExpressionParser;
use Tesseract\Datafilter\PostprocessEmptyFilterCheckInterface;
use Tesseract\Datafilter\PostprocessFilterInterface;
use Tesseract\Tesseract\Service\FilterBase;
use Tesseract\Tesseract\Utility\Utilities;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Data Filter service for the 'datafilter' extension.
 *
 * @author Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package TYPO3
 * @subpackage tx_datafilter
 */
class DataFilter extends FilterBase
{

    // Data Filter interface methods

    /**
     * Processeses the Data Filter's configuration and returns the filter structure
     *
     * @return array standardised filter structure
     */
    public function getFilterStructure()
    {
        // Initialise the filter structure, if not defined yet
        if (!isset($this->filter)) {
            $this->reset();
        }

        // Handle all parts of the filter configuration
        $this->defineFilterConfiguration($this->filterData['configuration']);
        $this->filter['logicalOperator'] = $this->filterData['logical_operator'];
        $this->defineLimit($this->filterData['limit_start'], $this->filterData['limit_offset'],
                $this->filterData['limit_pointer']);
        $this->defineSorting($this->filterData['orderby']);
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['datafilter']['postprocessReturnValue'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['datafilter']['postprocessReturnValue'] as $className) {
                /** @var $postProcessor PostprocessFilterInterface */
                $postProcessor = GeneralUtility::getUserObj($className);
                if ($postProcessor instanceof PostprocessFilterInterface) {
                    $postProcessor->postprocessFilter($this);
                }
            }
        }

        // Before returning, save the filter to session
        $this->saveFilter();
        return $this->filter;
    }

    /**
     * Returns true or FALSE depending on whether the filter can be considered empty or not.
     *
     * @return bool
     */
    public function isFilterEmpty()
    {
        $isEmpty = parent::isFilterEmpty();
        // Call hook to extend the empty filter check
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['datafilter']['postprocessEmptyFilterCheck'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['datafilter']['postprocessEmptyFilterCheck'] as $className) {
                /** @var $postProcessor PostprocessEmptyFilterCheckInterface */
                $postProcessor = GeneralUtility::getUserObj($className);
                if ($postProcessor instanceof PostprocessEmptyFilterCheckInterface) {
                    $isEmpty = $postProcessor->postprocessEmptyFilterCheck($isEmpty, $this);
                }
            }
        }
        return $isEmpty;
    }

    /**
     * Saves the filter into the session.
     *
     * @return void
     */
    public function saveFilter()
    {
        // If a session key has been set and TYPO3 is running in FE mode,
        // save the filter in session
        if (TYPO3_MODE === 'FE' && !empty($this->filterData['session_key'])) {
            // Assemble the key for session storage
            // It is either a general key name or a key name per page (with page id appended)
            if (empty($this->filterData['key_per_page'])) {
                $key = $this->filterData['session_key'];
            } else {
                $key = $this->filterData['session_key'] . '_' . $GLOBALS['TSFE']->id;
            }
            // NOTE: we save only the parsed part, as it is the only we are interested in keeping in session
            $GLOBALS['TSFE']->fe_user->setKey('ses', $key, $this->filter['parsed']);
        }
    }

    // Other methods

    /**
     * Takes the main filter configuration and assembles the "filters" part of the structure.
     *
     * @param string $configuration Filter configuration as stored in the DB record
     * @return void
     */
    protected function defineFilterConfiguration($configuration)
    {
        // Split the configuration into individual lines
        $configurationItems = Utilities::parseConfigurationField($configuration);
        foreach ($configurationItems as $index => $line) {
            // Parse the configuration line for possible subexpressions
            $parsedLine = ExpressionParser::evaluateString(
                    $line,
                    false
            );
            // Check if the line contains an explicit naming marker (double colon)
            $theLine = $parsedLine;
            if (strpos($parsedLine, '::') !== false) {
                // The line has a naming marker
                // The part before the double colon comes as a replacement for the numeric index,
                // the part after is the configuration line itself
                list($index, $theLine) = GeneralUtility::trimExplode('::', $parsedLine);
            }
            $matches = preg_split('/\s/', $theLine, -1, PREG_SPLIT_NO_EMPTY);
            // The first match is the field name, potentially prepended with the table name
            $fullField = array_shift($matches);
            $table = '';
            $field = trim($fullField);
            $mainFlag = false;
            $voidFlag = false;
            // The full field syntax may actually contain the "main" keyword,
            // the table name and the field name, each separated by dots (.)
            if (strpos($fullField, '.') !== false) {
                $fullFieldParts = GeneralUtility::trimExplode('.', $fullField);
                // The field is always the last part
                $field = array_pop($fullFieldParts);
                // If there's only one part left, it may be either a special keyword
                // or a table's name
                if (count($fullFieldParts) === 1) {
                    $part = array_pop($fullFieldParts);
                    if ($part === 'main') {
                        $mainFlag = true;
                    } elseif ($part === 'void') {
                        $voidFlag = true;
                    } else {
                        $table = $part;
                    }

                    // If there are more than one parts left, we expect the first part
                    // to be a special keyword and the second part to be a table's name
                } else {
                    // NOTE: if the part does not match a keyword, it is ignored, but an error is logged
                    $part = array_shift($fullFieldParts);
                    if ($part === 'main') {
                        $mainFlag = true;
                    } elseif ($part === 'void') {
                        $voidFlag = true;
                    } else {
                        $this->controller->addMessage(
                                'datafilter',
                                'Invalid keyword "' . htmlspecialchars($part) . '" ignored on line: ' . htmlspecialchars($line),
                                'Wrong filter configuration',
                                FlashMessage::WARNING
                        );
                    }
                    // Get the "last" part (if it's not the last, there's a syntax error)
                    $table = array_shift($fullFieldParts);
                    // Report error if last part is not really the last
                    if (count($fullFieldParts) > 0) {
                        $this->controller->addMessage(
                                'datafilter',
                                'Extra keyword(s) "' . htmlspecialchars(implode(', ',
                                        $fullFieldParts)) . '" ignored on line: ' . htmlspecialchars($line),
                                'Wrong filter configuration',
                                FlashMessage::WARNING
                        );
                    }
                }
            }
            // The second match is the operator
            $operator = strtolower(array_shift($matches));
            // If the operator starts with a !, it's a negation
            // Set the negation flag and strip the !
            if (strpos($operator, '!') === 0) {
                $negate = true;
                $operator = substr($operator, 1);
            } else {
                $negate = false;
            }
            // All the other matches are put together again to form the expression to be evaluated
            $valueExpression = implode(' ', $matches);
            try {
                $value = ExpressionParser::evaluateExpression($valueExpression);
                // Test special value "\clear_cache" (or its old value "clear_cache")
                // If the returned value is equal to this, it means the saved value must be removed
                if ($value === '\clear_cache' || $value === 'clear_cache') {
                    unset($this->filter['filters'][$index]);
                } else {
                    // If the value is an array, just use it straightaway
                    if (is_array($value)) {
                        $filterConfiguration = array(
                                'table' => $table,
                                'field' => $field,
                                'main' => $mainFlag,
                                'void' => $voidFlag,
                                'conditions' => array(
                                        0 => array(
                                                'operator' => $operator,
                                                'value' => $value,
                                                'negate' => $negate
                                        )
                                ),
                                'string' => $line
                        );
                        $this->filter['filters'][$index] = $filterConfiguration;
                        $this->saveParsedFilter($index, $table, $field, $operator, $value, $negate);

                        // The value is not an array and is not an empty string either
                    } elseif ($value !== '') {
                        $this->saveParsedFilter($index, $table, $field, $operator, $value, $negate);
                        // If value is an interval, this requires more processing
                        // The 2 boundaries of the interval must be extracted and the simple operator replaced by 2 conditions
                        $matches = array();
                        $matching = preg_match_all('/([\[\]])([^,]*),(\w*)([\[\]])/', $value, $matches);
                        // If the expression has matched, we have an interval
                        if ($matching === 1) {
                            $openingBracket = $matches[1][0];
                            $lowerBoundary = $matches[2][0];
                            $upperBoundary = $matches[3][0];
                            $closingBracket = $matches[4][0];
                            $conditions = array();
                            // Handle lower boundary, only if it's not * (= -infinity)
                            if ($lowerBoundary !== '*') {
                                if ($openingBracket === ']') {
                                    $operator = '>';
                                } else {
                                    $operator = '>=';
                                }
                                $conditions[] = array(
                                        'operator' => $operator,
                                        'value' => $lowerBoundary,
                                        'negate' => false
                                );
                            }
                            // Handle upper boundary, only if it's not * (= +infinity)
                            if ($upperBoundary !== '*') {
                                if ($closingBracket === '[') {
                                    $operator = '<';
                                } else {
                                    $operator = '<=';
                                }
                                $conditions[] = array(
                                        'operator' => $operator,
                                        'value' => $upperBoundary,
                                        'negate' => false
                                );
                            }
                            // If the condition contained a negation, issue warning that this cannot be used in a filter
                            if ($negate) {
                                $this->controller->addMessage('datfilter',
                                        'Negation ignored in condition: ' . $theLine,
                                        'Intervals cannot be negated',
                                        FlashMessage::NOTICE
                                );
                            }

                            // Normal filter, with no peculiarity, just set it
                        } else {
                            // If the value starts with a backslash, it's a special one
                            if (strpos($value, '\\') === 0) {
                                // Check as lowercase
                                $lowercaseValue = strtolower($value);
                                switch ($lowercaseValue) {
                                    case '\empty':
                                        $value = '\empty';
                                        break;
                                    case '\null';
                                        $value = '\null';
                                        break;
                                    case '\all':
                                        $value = '\all';
                                        break;
                                }
                            }
                            $conditions = array(
                                    0 => array(
                                            'operator' => $operator,
                                            'value' => $value,
                                            'negate' => $negate
                                    )
                            );
                        }
                        $filterConfiguration = array(
                                'table' => $table,
                                'field' => $field,
                                'main' => $mainFlag,
                                'void' => $voidFlag,
                                'conditions' => $conditions,
                                'string' => $line
                        );
                        $this->filter['filters'][$index] = $filterConfiguration;
                    }
                }
            } // The value could not be evaluated, skip to next value
            catch (\Exception $e) {
                continue;
            }
        }
    }

    /**
     * Takes the 3 parameters of the limit configuration and assembles the "limit" part of the structure.
     *
     * @param string $maxConfiguration Definition of the maximum number of records to display at a time
     * @param string $offsetConfiguration Definition of the offset, as a multiplier of $max
     * @param string $pointerConfiguration Definition of the direct pointer to a specific item
     * @return void
     */
    protected function defineLimit($maxConfiguration, $offsetConfiguration, $pointerConfiguration)
    {
        $max = 0;
        $offset = 0;
        $pointer = 0;
        if (!empty($maxConfiguration)) {
            try {
                $max = ExpressionParser::evaluateExpression($maxConfiguration);
                if (empty($offsetConfiguration)) {
                    $offset = 0;
                } else {
                    try {
                        $offset = ExpressionParser::evaluateExpression($offsetConfiguration);
                    } // If offset expression could not be evaluated, default to 0
                    catch (\Exception $e) {
                        $offset = 0;
                    }
                }
                if (empty($pointerConfiguration)) {
                    $pointer = 0;
                } else {
                    try {
                        $pointer = ExpressionParser::evaluateExpression($pointerConfiguration);

                    } // If startitem expression could not be evaluated, default to 0
                    catch (\Exception $e) {
                        $pointer = 0;
                    }
                }
            } // Do nothing special about exception, but exit process
            catch (\Exception $e) {
                return;
            }
        }
        $this->filter['limit'] = array('max' => $max, 'offset' => $offset, 'pointer' => $pointer);
    }

    /**
     * Takes the order by configuration and assembles the "orderby" part of the structure
     *
     * @param    string $orderConfiguration : order by configuration, as stored in the DB
     * @return    void
     */
    protected function defineSorting($orderConfiguration)
    {
        if (empty($orderConfiguration)) {
            return;
        }
        // Split the configuration into individual lines
        $configurationItems = Utilities::parseConfigurationField($orderConfiguration);
        $items = array();
        // In a first pass, we store all the configuration items as we go along,
        // storing their type and value
        $hasRandomOrdering = false;
        foreach ($configurationItems as $line) {
            // If the special \rand keyword (for random ordering) is used on any line, mark it as such
            // and interrupt the process
            if (strpos($line, '\rand') !== false) {
                $hasRandomOrdering = true;
                break;
            } else {
                $matches = GeneralUtility::trimExplode('=', $line, 1);
                $items[] = array('type' => $matches[0], 'value' => $matches[1]);
            }
        }
        // If the ordering structure contains at least one random ordering statement,
        // consider only this, as any other ordering wouldn't make any sense
        if ($hasRandomOrdering) {
            // NOTE: since it is the only ordering configuration, it always has index = 1
            $this->filter['orderby'][1] = array(
                    'table' => '',
                    'field' => '',
                    'order' => 'RAND',
                    'engine' => ''
            );

            // Otherwise parse the ordering statements line by line, expecting first a "field" directive
            // and then either "order" or "engine", or another "field" which indicates the start of a new
            // ordering configuration. Lines coming out of step are ignored.
        } else {
            $numItems = count($items);
            $lineCounter = 0;
            $newConfiguration = false;
            $configurations = array();
            $currentConfiguration = 0;
            $parseErrors = array();

            do {

                // If we don't have a configuration yet, the only acceptable type is "field"
                if ($items[$lineCounter]['type'] !== 'field' && count($configurations) === 0) {
                    $parseErrors[] = 'Expected "field" property on line: ' . htmlspecialchars(implode(' ',
                                    $items[$lineCounter]));

                    // If we have a "field" entry, it's a new configuration, set it up with default values
                } elseif (!$newConfiguration && $items[$lineCounter]['type'] === 'field') {
                    $newConfiguration = true;
                    $fullField = ExpressionParser::evaluateString($items[$lineCounter]['value']);
                    $table = '';
                    $field = $fullField;
                    if (strpos($fullField, '.') !== false) {
                        list($table, $field) = GeneralUtility::trimExplode('.', $fullField);
                    }
                    $currentConfiguration = $lineCounter;
                    $configurations[$currentConfiguration] = array(
                            'table' => $table,
                            'field' => $field,
                            'order' => 'ASC',
                            'engine' => ''
                    );

                    // Currently handling a configuration, add information if appropriate types are provided
                } else {
                    $newConfiguration = false;
                    switch ($items[$lineCounter]['type']) {
                        case 'order':
                            $order = ExpressionParser::evaluateString($items[$lineCounter]['value']);
                            $configurations[$currentConfiguration]['order'] = $order;
                            break;
                        case 'engine':
                            $engine = strtolower(ExpressionParser::evaluateString($items[$lineCounter]['value']));
                            // Ensure valid value
                            if (!in_array($engine, self::$allowedOrderingEngines, true)) {
                                $engine = '';
                            }
                            $configurations[$currentConfiguration]['engine'] = $engine;
                            break;
                        default:
                            $parseErrors[] = 'Invalid ordering configuration on line: ' . htmlspecialchars(implode(' = ',
                                            $items[$lineCounter]));
                    }
                }
                $lineCounter++;

            } while ($lineCounter < $numItems);
            // Set the found configurations
            $this->filter['orderby'] = $configurations;
            // Report parse errors, if any
            if (count($parseErrors) > 0) {
                $this->controller->addMessage(
                        'datafilter',
                        implode("\n", $parseErrors),
                        'Invalid configuration items for ordering',
                        FlashMessage::WARNING
                );
            }
        }
    }

    /**
     * Takes all the parameters of a filter and stores them in the "parsed" section of the filter.
     *
     * This section contains all filters stored in a special way, i.e. the table and field
     * are used as key and the operator and value become the value.
     * This syntax makes it possible to easily retrieve filter configurations when using the "session:" key in the
     * expression ExpressionParser.
     *
     * @param mixed $index Number or string used as a key for the given configuration
     * @param string $table Name of the table the filter applies to
     * @param string $field Name of the field the filter applies to
     * @param string $operator The operator of the condition
     * @param mixed $value The value of the condition
     * @param bool $negate TRUE if the operator is negated, FALSE otherwise
     * @return void
     */
    protected function saveParsedFilter($index, $table, $field, $operator, $value, $negate)
    {
        // Assemble storage key
        $keyForStorage = (empty($table)) ? '' : $table . '.';
        $keyForStorage .= $field;
        // Initialize storage, if necessary
        if (!isset($this->filter['parsed']['filters'][$keyForStorage])) {
            $this->filter['parsed']['filters'][$keyForStorage] = array();
        }
        // Compute values to store
        // If the value is an array, it is turned into a comma-separated string
        // NOTE: this will obviously fail with multidimensional arrays, but the alternative is to serialize
        // the value. This doesn't seem like a useful thing to do, since the values stored here are supposed
        // to be retrievable by the filters themselves, which won't be able to handle serialized values.
        // Thus the limitation to 1-dimensional arrays seems reasonable
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        $condition = (($negate) ? '!' : '') . $operator . ' ' . $value;
        $this->filter['parsed']['filters'][$keyForStorage][$index] = array(
                'condition' => $condition,
                'operator' => $operator,
                'value' => $value,
                'negate' => $negate
        );
    }

    /**
     * Performs necessary initialisations when an instance of this service
     * is called up several times.
     *
     * @return    void
     */
    public function reset()
    {
        $this->filter = array(
                'filters' => array(),
                'logicalOperator' => 'AND',
                'limit' => array(),
                'orderby' => array(),
                'parsed' => array(
                        'filters' => array()
                )
        );
    }
}
