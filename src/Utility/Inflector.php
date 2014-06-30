<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Sentient\Utility;

use Sentient\Application;

/**
 * Pluralize and singularize English words.
 *
 * Inflector pluralizes and singularizes English nouns.
 * Used by CakePHP's naming conventions throughout the framework.
 *
 * @link          http://book.cakephp.org/2.0/en/core-utility-libraries/inflector.html
 */
class Inflector implements InflectorInterface {

	/**
	 * @var \Silex\Application
	 */
	protected $app;

	/**
	 * Plural inflector rules
	 *
	 * @var array
	 */
	protected $_plural = array(
		'rules' => array(
			'/(s)tatus$/i' => '\1\2tatuses',
			'/(quiz)$/i' => '\1zes',
			'/^(ox)$/i' => '\1\2en',
			'/([m|l])ouse$/i' => '\1ice',
			'/(matr|vert|ind)(ix|ex)$/i' => '\1ices',
			'/(x|ch|ss|sh)$/i' => '\1es',
			'/([^aeiouy]|qu)y$/i' => '\1ies',
			'/(hive)$/i' => '\1s',
			'/(?:([^f])fe|([lre])f)$/i' => '\1\2ves',
			'/sis$/i' => 'ses',
			'/([ti])um$/i' => '\1a',
			'/(p)erson$/i' => '\1eople',
			'/(m)an$/i' => '\1en',
			'/(c)hild$/i' => '\1hildren',
			'/(buffal|tomat)o$/i' => '\1\2oes',
			'/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$/i' => '\1i',
			'/us$/i' => 'uses',
			'/(alias)$/i' => '\1es',
			'/(ax|cris|test)is$/i' => '\1es',
			'/s$/' => 's',
			'/^$/' => '',
			'/$/' => 's',
		),
		'uninflected' => array(
			'.*[nrlm]ese', '.*deer', '.*fish', '.*measles', '.*ois', '.*pox', '.*sheep', 'people'
		),
		'irregular' => array(
			'atlas' => 'atlases',
			'beef' => 'beefs',
			'brief' => 'briefs',
			'brother' => 'brothers',
			'cafe' => 'cafes',
			'child' => 'children',
			'cookie' => 'cookies',
			'corpus' => 'corpuses',
			'cow' => 'cows',
			'ganglion' => 'ganglions',
			'genie' => 'genies',
			'genus' => 'genera',
			'graffito' => 'graffiti',
			'hoof' => 'hoofs',
			'loaf' => 'loaves',
			'man' => 'men',
			'money' => 'monies',
			'mongoose' => 'mongooses',
			'move' => 'moves',
			'mythos' => 'mythoi',
			'niche' => 'niches',
			'numen' => 'numina',
			'occiput' => 'occiputs',
			'octopus' => 'octopuses',
			'opus' => 'opuses',
			'ox' => 'oxen',
			'penis' => 'penises',
			'person' => 'people',
			'sex' => 'sexes',
			'soliloquy' => 'soliloquies',
			'testis' => 'testes',
			'trilby' => 'trilbys',
			'turf' => 'turfs',
			'potato' => 'potatoes',
			'hero' => 'heroes',
			'tooth' => 'teeth',
			'goose' => 'geese',
			'foot' => 'feet'
		)
	);

	/**
	 * Singular inflector rules
	 *
	 * @var array
	 */
	protected $_singular = array(
		'rules' => array(
			'/(s)tatuses$/i' => '\1\2tatus',
			'/^(.*)(menu)s$/i' => '\1\2',
			'/(quiz)zes$/i' => '\\1',
			'/(matr)ices$/i' => '\1ix',
			'/(vert|ind)ices$/i' => '\1ex',
			'/^(ox)en/i' => '\1',
			'/(alias)(es)*$/i' => '\1',
			'/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|viri?)i$/i' => '\1us',
			'/([ftw]ax)es/i' => '\1',
			'/(cris|ax|test)es$/i' => '\1is',
			'/(shoe|slave)s$/i' => '\1',
			'/(o)es$/i' => '\1',
			'/ouses$/' => 'ouse',
			'/([^a])uses$/' => '\1us',
			'/([m|l])ice$/i' => '\1ouse',
			'/(x|ch|ss|sh)es$/i' => '\1',
			'/(m)ovies$/i' => '\1\2ovie',
			'/(s)eries$/i' => '\1\2eries',
			'/([^aeiouy]|qu)ies$/i' => '\1y',
			'/(tive)s$/i' => '\1',
			'/(hive)s$/i' => '\1',
			'/(drive)s$/i' => '\1',
			'/([le])ves$/i' => '\1f',
			'/([^rfo])ves$/i' => '\1fe',
			'/(^analy)ses$/i' => '\1sis',
			'/(analy|diagno|^ba|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
			'/([ti])a$/i' => '\1um',
			'/(p)eople$/i' => '\1\2erson',
			'/(m)en$/i' => '\1an',
			'/(c)hildren$/i' => '\1\2hild',
			'/(n)ews$/i' => '\1\2ews',
			'/eaus$/' => 'eau',
			'/^(.*us)$/' => '\\1',
			'/s$/i' => ''
		),
		'uninflected' => array(
			'.*[nrlm]ese', '.*deer', '.*fish', '.*measles', '.*ois', '.*pox', '.*sheep', '.*ss'
		),
		'irregular' => array(
			'foes' => 'foe',
			'waves' => 'wave',
		)
	);

	/**
	 * Words that should not be inflected
	 *
	 * @var array
	 */
	protected $_uninflected = array(
		'Amoyese', 'bison', 'Borghese', 'bream', 'breeches', 'britches', 'buffalo', 'cantus',
		'carp', 'chassis', 'clippers', 'cod', 'coitus', 'Congoese', 'contretemps', 'corps',
		'debris', 'diabetes', 'djinn', 'eland', 'elk', 'equipment', 'Faroese', 'flounder',
		'Foochowese', 'gallows', 'Genevese', 'Genoese', 'Gilbertese', 'graffiti',
		'headquarters', 'herpes', 'hijinks', 'Hottentotese', 'information', 'innings',
		'jackanapes', 'Kiplingese', 'Kongoese', 'Lucchese', 'mackerel', 'Maltese', '.*?media',
		'metadata', 'mews', 'moose', 'mumps', 'Nankingese', 'news', 'nexus', 'Niasese',
		'Pekingese', 'Piedmontese', 'pincers', 'Pistoiese', 'pliers', 'Portuguese',
		'proceedings', 'rabies', 'rice', 'rhinoceros', 'salmon', 'Sarawakese', 'scissors',
		'sea[- ]bass', 'series', 'Shavese', 'shears', 'siemens', 'species', 'swine', 'testes',
		'trousers', 'trout', 'tuna', 'Vermontese', 'Wenchowese', 'whiting', 'wildebeest',
		'Yengeese'
	);

	/**
	 * Default map of accented and special characters to ASCII characters
	 *
	 * @var array
	 */
	protected $_transliteration = array(
		'ä' => 'ae',
		'æ' => 'ae',
		'ǽ' => 'ae',
		'ö' => 'oe',
		'œ' => 'oe',
		'ü' => 'ue',
		'Ä' => 'Ae',
		'Ü' => 'Ue',
		'Ö' => 'Oe',
		'À' => 'A',
		'Á' => 'A',
		'Â' => 'A',
		'Ã' => 'A',
		'Å' => 'A',
		'Ǻ' => 'A',
		'Ā' => 'A',
		'Ă' => 'A',
		'Ą' => 'A',
		'Ǎ' => 'A',
		'à' => 'a',
		'á' => 'a',
		'â' => 'a',
		'ã' => 'a',
		'å' => 'a',
		'ǻ' => 'a',
		'ā' => 'a',
		'ă' => 'a',
		'ą' => 'a',
		'ǎ' => 'a',
		'ª' => 'a',
		'Ç' => 'C',
		'Ć' => 'C',
		'Ĉ' => 'C',
		'Ċ' => 'C',
		'Č' => 'C',
		'ç' => 'c',
		'ć' => 'c',
		'ĉ' => 'c',
		'ċ' => 'c',
		'č' => 'c',
		'Ð' => 'D',
		'Ď' => 'D',
		'Đ' => 'D',
		'ð' => 'd',
		'ď' => 'd',
		'đ' => 'd',
		'È' => 'E',
		'É' => 'E',
		'Ê' => 'E',
		'Ë' => 'E',
		'Ē' => 'E',
		'Ĕ' => 'E',
		'Ė' => 'E',
		'Ę' => 'E',
		'Ě' => 'E',
		'è' => 'e',
		'é' => 'e',
		'ê' => 'e',
		'ë' => 'e',
		'ē' => 'e',
		'ĕ' => 'e',
		'ė' => 'e',
		'ę' => 'e',
		'ě' => 'e',
		'Ĝ' => 'G',
		'Ğ' => 'G',
		'Ġ' => 'G',
		'Ģ' => 'G',
		'ĝ' => 'g',
		'ğ' => 'g',
		'ġ' => 'g',
		'ģ' => 'g',
		'Ĥ' => 'H',
		'Ħ' => 'H',
		'ĥ' => 'h',
		'ħ' => 'h',
		'Ì' => 'I',
		'Í' => 'I',
		'Î' => 'I',
		'Ï' => 'I',
		'Ĩ' => 'I',
		'Ī' => 'I',
		'Ĭ' => 'I',
		'Ǐ' => 'I',
		'Į' => 'I',
		'İ' => 'I',
		'ì' => 'i',
		'í' => 'i',
		'î' => 'i',
		'ï' => 'i',
		'ĩ' => 'i',
		'ī' => 'i',
		'ĭ' => 'i',
		'ǐ' => 'i',
		'į' => 'i',
		'ı' => 'i',
		'Ĵ' => 'J',
		'ĵ' => 'j',
		'Ķ' => 'K',
		'ķ' => 'k',
		'Ĺ' => 'L',
		'Ļ' => 'L',
		'Ľ' => 'L',
		'Ŀ' => 'L',
		'Ł' => 'L',
		'ĺ' => 'l',
		'ļ' => 'l',
		'ľ' => 'l',
		'ŀ' => 'l',
		'ł' => 'l',
		'Ñ' => 'N',
		'Ń' => 'N',
		'Ņ' => 'N',
		'Ň' => 'N',
		'ñ' => 'n',
		'ń' => 'n',
		'ņ' => 'n',
		'ň' => 'n',
		'ŉ' => 'n',
		'Ò' => 'O',
		'Ó' => 'O',
		'Ô' => 'O',
		'Õ' => 'O',
		'Ō' => 'O',
		'Ŏ' => 'O',
		'Ǒ' => 'O',
		'Ő' => 'O',
		'Ơ' => 'O',
		'Ø' => 'O',
		'Ǿ' => 'O',
		'ò' => 'o',
		'ó' => 'o',
		'ô' => 'o',
		'õ' => 'o',
		'ō' => 'o',
		'ŏ' => 'o',
		'ǒ' => 'o',
		'ő' => 'o',
		'ơ' => 'o',
		'ø' => 'o',
		'ǿ' => 'o',
		'º' => 'o',
		'Ŕ' => 'R',
		'Ŗ' => 'R',
		'Ř' => 'R',
		'ŕ' => 'r',
		'ŗ' => 'r',
		'ř' => 'r',
		'Ś' => 'S',
		'Ŝ' => 'S',
		'Ş' => 'S',
		'Ș' => 'S',
		'Š' => 'S',
		'ś' => 's',
		'ŝ' => 's',
		'ş' => 's',
		'ș' => 's',
		'š' => 's',
		'ſ' => 's',
		'Ţ' => 'T',
		'Ț' => 'T',
		'Ť' => 'T',
		'Ŧ' => 'T',
		'ţ' => 't',
		'ț' => 't',
		'ť' => 't',
		'ŧ' => 't',
		'Ù' => 'U',
		'Ú' => 'U',
		'Û' => 'U',
		'Ũ' => 'U',
		'Ū' => 'U',
		'Ŭ' => 'U',
		'Ů' => 'U',
		'Ű' => 'U',
		'Ų' => 'U',
		'Ư' => 'U',
		'Ǔ' => 'U',
		'Ǖ' => 'U',
		'Ǘ' => 'U',
		'Ǚ' => 'U',
		'Ǜ' => 'U',
		'ù' => 'u',
		'ú' => 'u',
		'û' => 'u',
		'ũ' => 'u',
		'ū' => 'u',
		'ŭ' => 'u',
		'ů' => 'u',
		'ű' => 'u',
		'ų' => 'u',
		'ư' => 'u',
		'ǔ' => 'u',
		'ǖ' => 'u',
		'ǘ' => 'u',
		'ǚ' => 'u',
		'ǜ' => 'u',
		'Ý' => 'Y',
		'Ÿ' => 'Y',
		'Ŷ' => 'Y',
		'ý' => 'y',
		'ÿ' => 'y',
		'ŷ' => 'y',
		'Ŵ' => 'W',
		'ŵ' => 'w',
		'Ź' => 'Z',
		'Ż' => 'Z',
		'Ž' => 'Z',
		'ź' => 'z',
		'ż' => 'z',
		'ž' => 'z',
		'Æ' => 'AE',
		'Ǽ' => 'AE',
		'ß' => 'ss',
		'Ĳ' => 'IJ',
		'ĳ' => 'ij',
		'Œ' => 'OE',
		'ƒ' => 'f',
	);

	/**
	 * Method cache array.
	 *
	 * @var array
	 */
	protected $_cache = array();

	/**
	 * The initial state of Inflector so reset() works.
	 *
	 * @var array
	 */
	protected $_initialState = array();

	/**
	 * @param Application $app
	 */
	public function __construct(Application $app) {

		$this->app = $app;
	}

	/**
	 * Cache inflected values, and return if already available
	 *
	 * @param string $type Inflection type
	 * @param string $key Original value
	 * @param string|bool $value Inflected value
	 * @return string Inflected value, from cache
	 */
	protected function _cache($type, $key, $value = false) {

		$key = '_' . $key;
		$type = '_' . $type;
		if ($value !== false) {
			$this->_cache[$type][$key] = $value;

			return $value;
		}
		if (!isset($this->_cache[$type][$key])) {
			return false;
		}

		return $this->_cache[$type][$key];
	}

	/**
	 * Clears Inflectors inflected value caches. And resets the inflection
	 * rules to the initial values.
	 *
	 * @return void
	 */
	public function reset() {

		if (empty($this->_initialState)) {
			$this->_initialState = get_class_vars(__CLASS__);

			return;
		}
		foreach ($this->_initialState as $key => $val) {
			if ($key !== '_initialState') {
				$this->{$key} = $val;
			}
		}
	}

	/**
	 * Adds custom inflection $rules, of either 'plural', 'singular' or 'transliteration' $type.
	 *
	 * ### Usage:
	 *
	 * {{{
	 * Inflector::rules('plural', array('/^(inflect)or$/i' => '\1ables'));
	 * Inflector::rules('plural', array(
	 *     'rules' => array('/^(inflect)ors$/i' => '\1ables'),
	 *     'uninflected' => array('dontinflectme'),
	 *     'irregular' => array('red' => 'redlings')
	 * ));
	 * Inflector::rules('transliteration', array('/å/' => 'aa'));
	 * }}}
	 *
	 * @param string $type The type of inflection, either 'plural', 'singular' or 'transliteration'
	 * @param array $rules Array of rules to be added.
	 * @param boolean $reset If true, will unset default inflections for all
	 *        new rules that are being defined in $rules.
	 * @return void
	 */
	public function rules($type, $rules, $reset = false) {

		$var = '_' . $type;

		switch ($type) {
			case 'transliteration':
				if ($reset) {
					$this->_transliteration = $rules;
				} else {
					$this->_transliteration = $rules + $this->_transliteration;
				}
				break;

			default:
				foreach ($rules as $rule => $pattern) {
					if (is_array($pattern)) {
						if ($reset) {
							$this->{$var}[$rule] = $pattern;
						} else {
							if ($rule === 'uninflected') {
								$this->{$var}[$rule] = array_merge($pattern, $this->{$var}[$rule]);
							} else {
								$this->{$var}[$rule] = $pattern + $this->{$var}[$rule];
							}
						}
						unset($rules[$rule], $this->{$var}['cache' . ucfirst($rule)]);
						if (isset($this->{$var}['merged'][$rule])) {
							unset($this->{$var}['merged'][$rule]);
						}
						if ($type === 'plural') {
							$this->_cache['pluralize'] = $this->_cache['tableize'] = array();
						} elseif ($type === 'singular') {
							$this->_cache['singularize'] = array();
						}
					}
				}
				$this->{$var}['rules'] = $rules + $this->{$var}['rules'];
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function pluralize($word) {

		if (isset($this->_cache['pluralize'][$word])) {
			return $this->_cache['pluralize'][$word];
		}

		if (!isset($this->_plural['merged']['irregular'])) {
			$this->_plural['merged']['irregular'] = $this->_plural['irregular'];
		}

		if (!isset($this->_plural['merged']['uninflected'])) {
			$this->_plural['merged']['uninflected'] = array_merge($this->_plural['uninflected'], $this->_uninflected);
		}

		if (!isset($this->_plural['cacheUninflected']) || !isset($this->_plural['cacheIrregular'])) {
			$this->_plural['cacheUninflected'] = '(?:' . implode('|', $this->_plural['merged']['uninflected']) . ')';
			$this->_plural['cacheIrregular'] = '(?:' . implode('|', array_keys($this->_plural['merged']['irregular'])) . ')';
		}

		if (preg_match('/(.*)\\b(' . $this->_plural['cacheIrregular'] . ')$/i', $word, $regs)) {
			$this->_cache['pluralize'][$word] = $regs[1] . substr($word, 0, 1) . substr($this->_plural['merged']['irregular'][strtolower($regs[2])], 1);

			return $this->_cache['pluralize'][$word];
		}

		if (preg_match('/^(' . $this->_plural['cacheUninflected'] . ')$/i', $word, $regs)) {
			$this->_cache['pluralize'][$word] = $word;

			return $word;
		}

		foreach ($this->_plural['rules'] as $rule => $replacement) {
			if (preg_match($rule, $word)) {
				$this->_cache['pluralize'][$word] = preg_replace($rule, $replacement, $word);

				return $this->_cache['pluralize'][$word];
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function singularize($word) {

		if (isset($this->_cache['singularize'][$word])) {
			return $this->_cache['singularize'][$word];
		}

		if (!isset($this->_singular['merged']['uninflected'])) {
			$this->_singular['merged']['uninflected'] = array_merge(
				$this->_singular['uninflected'],
				$this->_uninflected
			);
		}

		if (!isset($this->_singular['merged']['irregular'])) {
			$this->_singular['merged']['irregular'] = array_merge(
				$this->_singular['irregular'],
				array_flip($this->_plural['irregular'])
			);
		}

		if (!isset($this->_singular['cacheUninflected']) || !isset($this->_singular['cacheIrregular'])) {
			$this->_singular['cacheUninflected'] = '(?:' . implode('|', $this->_singular['merged']['uninflected']) . ')';
			$this->_singular['cacheIrregular'] = '(?:' . implode('|', array_keys($this->_singular['merged']['irregular'])) . ')';
		}

		if (preg_match('/(.*)\\b(' . $this->_singular['cacheIrregular'] . ')$/i', $word, $regs)) {
			$this->_cache['singularize'][$word] = $regs[1] . substr($word, 0, 1) . substr($this->_singular['merged']['irregular'][strtolower($regs[2])], 1);

			return $this->_cache['singularize'][$word];
		}

		if (preg_match('/^(' . $this->_singular['cacheUninflected'] . ')$/i', $word, $regs)) {
			$this->_cache['singularize'][$word] = $word;

			return $word;
		}

		foreach ($this->_singular['rules'] as $rule => $replacement) {
			if (preg_match($rule, $word)) {
				$this->_cache['singularize'][$word] = preg_replace($rule, $replacement, $word);

				return $this->_cache['singularize'][$word];
			}
		}
		$this->_cache['singularize'][$word] = $word;

		return $word;
	}

	/**
	 * {@inheritdoc}
	 */
	public function camelize($lowerCaseAndUnderscoredWord) {

		if (!($result = $this->_cache(__FUNCTION__, $lowerCaseAndUnderscoredWord))) {
			$result = str_replace(' ', '', $this->humanize($lowerCaseAndUnderscoredWord));
			$this->_cache(__FUNCTION__, $lowerCaseAndUnderscoredWord, $result);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function underscore($camelCasedWord) {

		if (!($result = $this->_cache(__FUNCTION__, $camelCasedWord))) {
			$result = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $camelCasedWord));
			$this->_cache(__FUNCTION__, $camelCasedWord, $result);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function humanize($lowerCaseAndUnderscoredWord) {

		if (!($result = $this->_cache(__FUNCTION__, $lowerCaseAndUnderscoredWord))) {
			$result = str_replace('_', ' ', $lowerCaseAndUnderscoredWord);
			$this->_cache(__FUNCTION__, $lowerCaseAndUnderscoredWord, $result);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function tableize($className) {

		if (!($result = $this->_cache(__FUNCTION__, $className))) {
			$result = $this->pluralize($this->underscore($className));
			$this->_cache(__FUNCTION__, $className, $result);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function classify($tableName) {

		if (!($result = $this->_cache(__FUNCTION__, $tableName))) {
			$result = $this->camelize($this->singularize($tableName));
			$this->_cache(__FUNCTION__, $tableName, $result);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function variable($string) {

		if (!($result = $this->_cache(__FUNCTION__, $string))) {
			$camelized = $this->camelize($this->underscore($string));
			$replace = strtolower(substr($camelized, 0, 1));
			$result = preg_replace('/\\w/', $replace, $camelized, 1);
			$this->_cache(__FUNCTION__, $string, $result);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function slug($string, $replacement = '_') {

		$quotedReplacement = preg_quote($replacement, '/');

		$map = array(
			'/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
			'/[\s\p{Zs}]+/mu' => $replacement,
			sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
		);

		$string = str_replace(array_keys($this->_transliteration), array_values($this->_transliteration), $string);

		return preg_replace(array_keys($map), array_values($map), $string);
	}

}
