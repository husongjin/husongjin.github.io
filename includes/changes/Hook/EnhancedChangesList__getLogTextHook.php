<?php

namespace MediaWiki\Hook;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
use EnhancedChangesList;
use RecentChange;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface EnhancedChangesList__getLogTextHook {
	/**
	 * Use this hook to alter, remove, or add to the links of a group of changes in
	 * EnhancedChangesList.
	 *
	 * @since 1.35
	 *
	 * @param EnhancedChangesList $changesList
	 * @param string[] &$links Links generated by EnhancedChangesList
	 * @param RecentChange[] $block RecentChange objects in that block
	 * @return bool|void True or no return value to continue, or false to omit
	 *   this line from recentchanges
	 */
	public function onEnhancedChangesList__getLogText( $changesList, &$links,
		$block
	);
}