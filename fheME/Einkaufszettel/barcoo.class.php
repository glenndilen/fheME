<?php
/**
 *  This file is part of fheME.

 *  fheME is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.

 *  fheME is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 *  2007 - 2013, Rainer Furtmeier - Rainer@Furtmeier.IT
 */

class barcoo {
	public function startSeach($EAN){
		$artikel = array();

		$page = file_get_contents("http://www.barcoo.com/$EAN?source=pb");

		preg_match("/\<meta property=\"og:title\" content=\"(.*)\" \/\>/ismU", $page, $matches);

		if(count($matches) == 0)
			return $artikel;
		
		$artikel["name"] = ucwords(strtolower($matches[1]));

		return $artikel;
	}
}
?>