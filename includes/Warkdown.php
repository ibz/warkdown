<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @file
 */


use MediaWiki\Shell\Shell;

class Warkdown {
	/**
	 * Bind the renderMarkdown function to the <markdown> tag
	 * @param Parser $parser
	 */
	public static function init( Parser $parser ) {
		$parser->setHook( 'markdown', [ self::class, 'renderMarkdown' ] );
	}

	public static function renderMarkdown($in, array $param, Parser $parser, PPFrame $frame) {
		$title = '';
		if (isset($param['src'])) {
			$hackmd_id = $param['src'];
			$result = Shell::command("/usr/bin/env", "python3", "/var/www/html/extensions/Warkdown/src/render_hackmd_markdown.py", $hackmd_id)->execute();
			$rendered = $result->getStdout();
			if (strlen(trim($rendered)) === 0) {
				$Parsedown = new Parsedown();
				$rendered = $Parsedown->text("```\n" . $result->getStderr() . "\n```");
			} else {
				$title_span = '<span id="markdown" class="mw-headline">HackMD content</span>';
				$edit_link = '<span class="mw-editsection"><span class="mw-editsection-bracket">[</span><a href="https://hackmd.io/' . $hackmd_id . '?edit" target="_blank">edit on HackMD</a><span class="mw-editsection-bracket">]</span></span>';
				$title = '<h2>' . $title_span . $edit_link . '</h2>';
			}
		} else {
			$Parsedown = new Parsedown();
			$rendered = $Parsedown->text($in);
		}

		if (isset($param['class'])) {
			$param['class'] = 'markdown ' . $param['class'];
		} else {
			$param['class'] = 'markdown';
		}

		return Html::rawElement('div', $param, $title . $rendered);
	}
}
