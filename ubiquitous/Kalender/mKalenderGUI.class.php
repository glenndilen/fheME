<?php
/*
 *  This file is part of phynx.

 *  phynx is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.

 *  phynx is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 *  2007 - 2013, Rainer Furtmeier - Rainer@Furtmeier.IT
 */
class mKalenderGUI extends mKalender implements iGUIHTML2 {
	function  __construct() {
		parent::__construct();
		
		$this->customize();
	}
	
	public static $colors = array();
	
	public function getHTML($id){
		
		#$_SESSION["BPS"]->unregisterClass(get_class($this));
		$defaultAnsicht = "monat";
		if(Applications::activeApplication() == "personalKartei")
			$defaultAnsicht = "jahr";
		
		$ansicht = mUserdata::getUDValueS("KalenderAnsicht", $defaultAnsicht);
		#$ansicht = $ansicht->getUDValue("KalenderAnsicht");
		#if($ansicht == null) $ansicht = "monat";
		
		$display = mUserdata::getUDValueS("KalenderDisplay".ucfirst($ansicht), "0");

		
		$BThis = new Button("", "arrow_down", "iconicG");
		$BThis->style("float:left;margin-top:-6px;margin-right:5px;");
		$Calendars = "";
		Registry::reset("Kalender");
		while($C = Registry::callNext("Kalender", "categories")){
			if(!$C)
				continue;
			
			foreach($C AS $tab)
				$Calendars .= "<div onclick=\"$tab->onclick\">".($tab->isCurrent ? $BThis : "")." $tab->label</div>";
			
		}
		
		
		
		
		
		// <editor-fold defaultstate="collapsed" desc="styles">
		$html = "
		
		<style type=\"text/css\">
		.Day {
			-moz-user-select:none;
			border-left:1px solid #EEE;
			border-bottom:1px solid #EEE;
		}
		
		/*.Day:hover {
			border-style:solid;
			border-width:1px;
			padding:2px;
		}*/

		.dayOptions {
			display:none;
		}

		.Day:hover .dayOptions {
			display:inline;
		}

		.Termin {
			position:relative;
			left:44px;
			cursor:pointer;
			width:150px;
			float:left;
			border-style:solid;
			border-width:1px;
			margin-right:3px;
		}
		
		.KalenderButton {
			opacity:0.5;
		}
		
		.KalenderButton:hover {
			opacity:1;
		}
		
		.KalenderUser {
			margin-left:10px;
		}
		
		.KalenderUser div {
			padding:10px;
			padding-top:10px;
			padding-bottom:5px;
			display:inline-block;
			margin-right:20px;
			cursor:pointer;
			min-width:150px;
		}
		
		.cellHeight {
		}
		
		.ui-datepicker {
			width: auto;
		}

		#contentScreen tr:hover {
			background-color:inherit;
		}

		#calendar1stMonth .ui-datepicker-prev, #calendar1stMonth .ui-datepicker-next/*,
		#calendar2ndMonth .ui-datepicker-prev, #calendar2ndMonth .ui-datepicker-next */{
			display:none;
		}
		
		#calendar1stMonth .ui-widget-content,
		#calendar2ndMonth .ui-widget-content, 
		#calendar2ndMonth .ui-widget-content .ui-state-default,
		#calendar1stMonth .ui-widget-content .ui-state-default {
			border:0px;
		}
		
		#calendar1stMonth .ui-datepicker-header,
		#calendar2ndMonth .ui-datepicker-header {
			border:0px;
		}
		
		/*@media only screen and (max-height: 820px) {
			.cellHeight {
				height:55px;
			}
		}*/
		</style>";
		// </editor-fold>

		$BLeft = new Button("Zurück","back", "icon");
		$BLeft->rmePCR("mKalender", "", "setDisplay", $display - 1, "contentManager.loadFrame('contentScreen','mKalender');");
		$BLeft->style("margin-right:10px;");

		$BRight = new Button("Weiter","navigation", "icon");
		$BRight->rmePCR("mKalender", "", "setDisplay", $display + 1, "contentManager.loadFrame('contentScreen','mKalender');");
		$BRight->style("margin-right:10px;");

		$BToday = new Button("Aktuelles Datum","down", "icon");
		$BToday->rmePCR("mKalender", "", "setToday", '', "contentManager.loadFrame('contentScreen','mKalender');");
		$BToday->style("margin-right:10px;");

		
		$BJahr = new Button("Jahr","./ubiquitous/Kalender/month.png", "icon");
		$BJahr->rmePCR("mKalender", "", "setView", "jahr", "contentManager.loadFrame('contentScreen','mKalender');");
		$BJahr->style("margin-right:10px;".($ansicht != "jahr" ? "opacity:0.5;" : ""));
		$BJahr->id("jahrButton");

		$BMonat = new Button("Monat","./ubiquitous/Kalender/month.png", "icon");
		$BMonat->rmePCR("mKalender", "", "setView", "monat", "contentManager.loadFrame('contentScreen','mKalender');");
		$BMonat->style("margin-right:10px;".($ansicht != "monat" ? "opacity:0.5;" : ""));
		$BMonat->id("monatButton");

		$BWoche = new Button("Woche","./ubiquitous/Kalender/workweek.png", "icon");
		$BWoche->rmePCR("mKalender", "", "setView", "woche", "contentManager.loadFrame('contentScreen','mKalender');");
		$BWoche->style("margin-right:10px;".($ansicht != "woche" ? "opacity:0.5;" : ""));
		$BWoche->id("wocheButton");

		$BTag = new Button("Tag","./ubiquitous/Kalender/day.png", "icon");
		$BTag->rmePCR("mKalender", "", "setView", "tag", "contentManager.loadFrame('contentScreen','mKalender');");
		$BTag->style("margin-right:10px;".($ansicht != "tag" ? "opacity:0.5;" : ""));
		$BTag->id("tagButton");
		
		if(Applications::activeApplication() == "personalKartei"){
			$BTag = "";
			$BWoche = "";
			$BMonat = "";
		}
		
		if(Applications::activeApplication() != "personalKartei")
			$BJahr = "";
		

		$ST = new HTMLTable(1);
		$ST->setColClass(1, "");
		#$ST->setTableStyle("width:40px;margin:0px;margin-right:-215px;float:right;/*margin-right:-50px;margin-top:95px;*/");
		
		$newWindow = new Button("Kalender in neuem Fenster öffnen", "new_window", "iconicL");
		$newWindow->style("margin-right:10px;");
		$newWindow->newSession("Mail", Applications::activeApplication(), "mKalender", "Kalender");
		if(Session::physion())
			$newWindow = "";
		
		$reminder = "";
		if(Session::isPluginLoaded("mReminder")){
			$reminder = Reminder::getButton();
			$reminder->style("margin-right:10px;");
		}
		
		$ST->addRow("<div id=\"calendar1stMonth\"></div>");
		$ST->addRow("<div id=\"calendar2ndMonth\"></div>");
		
		
		$TCalendars = "<div>";
		if(trim($Calendars) != "")
			$TCalendars .= "<div class=\"KalenderUser\">".$Calendars."</div>";		
		$TCalendars .= "</div>";
		
		$pCalButton = "";
		if(Session::isPluginLoaded("mpCal")){
			$pCalButton = pCal::getTBButton();
			$pCalButton->type("icon");
			$pCalButton->style("margin-right:10px;");
		}
		
		$GoogleButton = "";
		$GoogleDLButton = "";
		if(Session::isPluginLoaded("mGoogle")){
			$GoogleButton = LoginData::getButtonU("GoogleAccountUserPass", "Google-Daten bearbeiten", "./ubiquitous/Google/google.png");
			$GoogleButton->type("icon");
			$GoogleButton->style("margin-right:10px;");
			

			$GoogleDLButton = new Button("Daten herunterladen", "./ubiquitous/Google/googleDL.png", "icon");
			$GoogleDLButton->popup("", "Daten herunterladen", "Google", "-1", "syncByDateRange", array("'".date("Y-m-d", $firstDay)."'", "'".date("Y-m-d", $lastDay)."'"));
			$GoogleDLButton->style("margin-right:10px;");
		}
		
		$xCalButton = "";
		if (Session::isPluginLoaded("mxCal")) {
			$xCalButton = xCal::getButton();
			$xCalButton->style("margin-right:10px;");
		}
		
		$BShare = new Button("Kalender teilen", "fork", "iconicL");
		$BShare->popup("", "Kalender teilen", "mKalender", "-1", "share");
		$BShare->style("margin-right:10px;");
			
		
		#$AWVButton = new Button("Müllabfuhr-Daten herunterladen", "trash_stroke", "iconicL");
		#$AWVButton->popup("", "Müllabfuhr-Daten", "mKalender", "-1", "downloadTrashData");
		$AWVButton = "";
		
		$ST->addRow($pCalButton.$GoogleButton.$GoogleDLButton);
		
		$TC = "KalenderView".ucfirst($ansicht);
		$TC = new $TC();
		
		$html .= "
		<div style=\"width:205px;float:right;margin-right:40px;\">
				<div style=\"padding-top:30px;padding-bottom:15px;padding-left:0px;\">
					$newWindow$BShare$AWVButton$xCalButton$reminder
				</div>
		</div>
		
		<div style=\"margin-right:270px;\">
		<div id=\"KalenderTitle\" class=\"prettyTitle\">
			
			<span style=\"float:right;\">
				$BLeft$BToday$BRight
			</span>
			<div style=\"float:right;margin-right:100px;\">$BTag$BWoche$BMonat$BJahr</div>
			".$TC->getTitle()."
		</div>
		</div>
		<div id=\"KalenderAuswahl\">
			$TCalendars
		</div>
		".$TC->getHeader()."
		<div id=\"KalenderWrapper\" style=\"overflow:auto;\">
			".($ansicht != "jahr" ? "
			<div style=\"width:205px;float:right;margin-right:40px;\">
				<div style=\"height:23px;\"></div>$ST
			</div>" : "")."
			<div style=\"".($ansicht != "jahr" ? "margin-right:270px;" : "")."\">

			".$TC->getTable($this)."
			</div>
		</div>";

		$nextMonths = new Datum();
		$nextMonths->setToMonth1st();
		$thisMonth = $nextMonths->time();
		$nextMonths->addMonth();
		$nextMonth = $nextMonths->time();
		$html .= OnEvent::script("\$j(function() {
		\$j('#calendar1stMonth').datepicker({ minDate: '".date("d.m.Y", $thisMonth)."'".($TC->getCurrent()->time() < $nextMonth ? ",defaultDate: '".date("d.m.Y",$TC->getCurrent()->time())."'" : "").", showWeek: true,  showOtherMonths: true, onSelect: function(dateText, inst) { var day = Math.round(+new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay, 0, 1, 0)/1000); ".OnEvent::rme($this, "setView", array("'tag'", "day"), "function(){ ".OnEvent::reload("Screen")." }")." } });
			
		\$j('.KalenderUser div[class!=backgroundColor1]').hover(function(){ \$j(this).addClass('backgroundColor2'); }, function(){ \$j(this).removeClass('backgroundColor2'); });
		fitKalender();
			
		\$j(window).resize(function() {
			fitKalender();
		});
		
		});
		
		function fitKalender(){
			if(!\$j('#KalenderTitle').length)
				return;
			
			//console.log(\$j('#KalenderTitle').outerHeight());
			//console.log(\$j('#KalenderAuswahl').outerHeight());
			var height = (contentManager.maxHeight() - \$j('#KalenderAuswahl').outerHeight() - \$j('#KalenderTitle').outerHeight() - \$j('#KalenderHeader').outerHeight()) + 4;
			var width = contentManager.maxWidth();
			
			\$j('#KalenderWrapper').css('height', height);
			\$j('#KalenderWrapper').css('width', width);

			var cellHeight = (height - \$j('#KalenderTable tr:first th').parent().outerHeight()) / (\$j('#KalenderTable tr').length - ".(($ansicht == "monat" OR $ansicht == "jahr") ? "1" : "0").") - 1;
			\$j('.cellHeight').css('height', cellHeight+'px');
			\$j('.innerCellHeight').css('height', (cellHeight - \$j('.innerCellTitle:visible').outerHeight())+'px');
			
			if(\$j('#KalenderHeader').length > 0){
				//console.log(\$j('#KalenderHeader tr:first th'));
				\$j('#KalenderTable tr:first td').each(function(k, v){
					
					\$j(\$j('#KalenderHeader tr:first th')[k]).css('width', \$j(v).width());
				});
			}
			
			if(\$j('#tagDiv').length) {
				\$j('#tagDiv').css('width', \$j('#KalenderTable tr').width()+'px');
				\$j('#tagDiv').animate({scrollTop: 7*40}, 0);
				var pos = \$j('#tagDiv').offset();
				pos.position = 'absolute';

				\$j('#tagDiv').css(pos)
			}
		}")."
		<style type=\"text/css\">
			".($TC->getCurrent()->time() < $thisMonth ? "#calendar1stMonth .ui-state-default { border: 1px solid #D3D3D3; background-color:transparent; }" : "")."
			".($TC->getCurrent()->time() < $nextMonth ? "#calendar2ndMonth .ui-state-default { border: 1px solid #D3D3D3; background-color:transparent; }" : "")."
			.ui-datepicker-week-col { color:grey; text-align:left; }
			tr td.ui-datepicker-week-col {text-align:left;font-size:10px; }
			/*.ui-datepicker-week-end { background-color:#DDD; }*/
		</style>";

		$html .= OnEvent::script("\$j(function() {
		\$j('#calendar2ndMonth').datepicker({ minDate: '".date("d.m.Y",$nextMonth)."'".($TC->getCurrent()->time() >= $nextMonth ? ", defaultDate: '".date("d.m.Y",$TC->getCurrent()->time())."'" : "").", showWeek: true, showOtherMonths: true,  onSelect: function(dateText, inst) { var day = Math.round(+new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay, 0, 1, 0)/1000); ".OnEvent::rme($this, "setView", array("'tag'", "day"), "function(){ ".OnEvent::reload("Screen")." }")." } });
	});");
		
		return $html;
	}
	
	function setView($to, $spec = null){
		$U = new mUserdata();
		$U->setUserdata("KalenderAnsicht",$to);

		if($to == "tag" AND $spec != null){
			$days = ceil(($spec - time()) / (3600 * 24));
			$this->setDisplay($days, $to);
		}
	}
	
	function setToday(){
		$this->setDisplay(0);
	}
	
	function setDisplay($to, $type = null){
		if($type == null){
			$ansicht = new mUserdata();
			$ansicht = $ansicht->getUDValue("KalenderAnsicht");
			if($ansicht == null) $ansicht = "monat";
			$type = $ansicht;
		}
		
		#$_SESSION["BPS"]->registerClass(get_class($this));
		#$_SESSION["BPS"]->setACProperty("noReloadRight","true");
		
		$U = new mUserdata();
		$U->setUserdata("KalenderDisplay".ucfirst($type),$to);
	}

	function getInfo($className, $classID, $day){
		$C = new $className($classID);
		echo $C->getCalendarDetails($className, $classID)->getInfo($day);
	}
	
	/*
	 * proxy for consistent popups
	 */
	public function newTodo($id, $date = null, $targetClass = null, $targetClassID = null, $time = null, $description = null, $location = null){
		$mT = new mTodoGUI();
		$mT->editInPopup($id, $date, $targetClass, $targetClassID, $time, $description, $location);
	}
	
	/*
	 * proxy for consistent popups
	 */
	public function editInPopup($className, $classID, $method){
		#"'".$this->className."'", $this->classID, "'{$this->editable[0]}'"
		$C = new $className(-1);
		$C->$method($classID);
	}
	
	public function getOverviewContent($echo = true){
		$time = mktime(0, 0, 1);
		$Datum = new Datum($time);
		$Datum->addMonth();
		$lastTime = $Datum->time();
		$Datum->subMonth();
		$Woche = date("W");
		$K = $this->getData($time, $lastTime);
		
		$hasEvent = array();
		
		$html = "<div class=\"touchHeader\"><span class=\"lastUpdate\" id=\"lastUpdatemKalenderGUI\"></span><p>Kalender</p></div><div style=\"padding:10px;padding-left:0px;\">";
		
		$html .= "<div style=\"width:25px;float:left;margin-right:5px;color:grey;font-size:11px;\">%%SMALLCALCONTENT%%</div>";
		
		$html .= "<div style=\"border-bottom-width:1px;border-bottom-style:dashed;padding:3px;margin-left:30px;\" class=\"borderColor1\">Heute</div>";
		$list = new HTMLList();
		$list->addListStyle("list-style-type:none;margin-left:30px;");
		
		$events = $K->getEventsOnDay(date("dmY", $Datum->time()));
		if($events != null AND count($events) > 0)
			foreach($events AS $ev)
				foreach($ev AS $KE){
					$hasEvent[date("d", $K->parseDay($KE->getDay()))] = true;
					
					$B = new Button("", $KE->icon(), "icon");
					$B->style("float:left;margin-right:5px;margin-bottom:10px;");

					$list->addItem("$B<b style=\"font-size:15px;\">".$KE->title()."</b><br /><small>".Datum::getGerWeekArray(date("w", $K->parseDay($KE->getDay()))).", ".Util::CLDateParser($K->parseDay($KE->getDay()))." ".Util::CLTimeParser($K->parseTime($KE->getTime()))."</small>");
				}
		if(count($events) == 0)
			$list->addItem("<span style=\"color:grey;\">Kein Eintrag</span>");
				
		$html .= $list;
		$Datum->addDay();
		
		$html .= "<div style=\"border-bottom-width:1px;border-bottom-style:dashed;padding:3px;margin-top:15px;margin-left:30px;\" class=\"borderColor1\">Morgen</div>";
		$list = new HTMLList();
		$list->addListStyle("list-style-type:none;margin-left:30px;");
		
		$events = $K->getEventsOnDay(date("dmY", $Datum->time()));
		if($events != null AND count($events) > 0)
			foreach($events AS $ev)
				foreach($ev AS $KE){
					$hasEvent[date("d", $K->parseDay($KE->getDay()))] = true;
					
					$B = new Button("", $KE->icon(), "icon");
					$B->style("float:left;margin-right:5px;margin-bottom:10px;");

					$list->addItem("$B<b style=\"font-size:15px;\">".$KE->title()."</b><br /><small>".Datum::getGerWeekArray(date("w", $K->parseDay($KE->getDay()))).", ".Util::CLDateParser($K->parseDay($KE->getDay()))." ".Util::CLTimeParser($K->parseTime($KE->getTime()))."</small>");
				}
		if(count($events) == 0)
			$list->addItem("<span style=\"color:grey;\">Kein Eintrag</span>");
		
		$html .= $list;
		$Datum->addDay();
		
		$html .= "<div style=\"border-bottom-width:1px;border-bottom-style:dashed;padding:3px;margin-top:15px;margin-left:30px;\" class=\"borderColor1\">Später</div>";
		$list = new HTMLList();
		$list->addListStyle("list-style-type:none;margin-left:30px;");
		$c = 0;
		while($Datum->time() < $lastTime){
			
			$events = $K->getEventsOnDay(date("dmY", $Datum->time()));
			if($events != null AND count($events) > 0)
				foreach($events AS $ev)
					foreach($ev AS $KE){
						$hasEvent[date("d", $K->parseDay($KE->getDay()))] = true;
						
						$B = new Button("", $KE->icon(), "icon");
						$B->style("float:left;margin-right:5px;margin-bottom:10px;");

						$list->addItem("$B<b style=\"font-size:15px;\">".$KE->title()."</b><br /><small>".Datum::getGerWeekArray(date("w", $K->parseDay($KE->getDay()))).", ".Util::CLDateParser($K->parseDay($KE->getDay()))."</small>");
						if(date("W", $K->parseDay($KE->getDay())) > $Woche + 1)
							$list->addItemStyle ("color:grey;");
						$c++;
					}
			
			$Datum->addDay();
		}
		if($c == 0)
			$list->addItem("<span style=\"color:grey;\">Kein Eintrag</span>");
		
		$html .= $list."</div>";
		
		
		$smallCal = "";
		$DatumC = clone $Datum;
		for($i = 0; $i < 14; $i++){
			$smallCal .= "<div style=\"padding:5px;text-align:right;".(isset($hasEvent[date("d", $DatumC->time())]) ? "color:black;" : "")."\" ".(isset($hasEvent[date("d", $DatumC->time())]) ? "class=\"backgroundColor3\"" : "")."\">".date("d", $DatumC->time())."</div>";
			
			$DatumC->addDay();
		}
		
		$html = str_replace("%%SMALLCALCONTENT%%", $smallCal, $html);
		
		if($echo)
			echo $html;
		
		return $html;
	}
	
	public static function getOverviewPlugin(){
		$P = new overviewPlugin("mKalenderGUI", "Kalender", 130);
		$P->updateInterval(900);
		
		return $P;
	}
	
	public function getRepeatable($targetClass, $targetClassID, $targetClassMethod){
		$C = new $targetClass($targetClassID);
		echo $C->$targetClassMethod($targetClassID);
	}
	
	public function getInviteForm($targetClass, $targetClassId, $targetClassMethod) {
		$class = new $targetClass($targetClassId);
		echo $class->$targetClassMethod($targetClassId);
	}
	
	public function share(){
		$fields = array();
		$US = Users::getUsers();
		while($U = $US->getNextEntry()){
			if($U->getID() == Session::currentUser()->getID())
				continue;
			
			$fields[] = "User".$U->getID();
		}
		$US->resetPointer();
		
		echo "<p>In diesem Fenster bestimmen Sie andere Systembenutzer, die auf Ihren Kalender zugreifen können. Falls es andere Systembenutzer gibt.</p>";
		
		if(count($fields) == 0)
			return;
		
		$F = new HTMLForm("shareMailBox", $fields);
		$F->getTable()->setColWidth(1, 120);
		
		#$F->setValue("MailKontoID", $MailKontoID);
		#$F->setType("MailKontoID", "hidden");
		
		while($U = $US->getNextEntry()){
			$F->setLabel("User".$U->getID(), $U->A("name"));
			$F->setType("User".$U->getID(), "select", null, array("none" => "kein Zugriff", "read" => "lesen", "read.create" => "lesen und erstellen"));
		}
		
		$UD = new mUserdata();
		$shareWith = $UD->getAsArray("shareCalendar");
		
		foreach($shareWith AS $v => $n){
			$v = str_replace("shareCalendarTo", "", $v);
			
			$F->setValue("User$v", $n);
		}
		
		$F->setSaveRMEPCR("Speichern", "", "mKalender", "-1", "saveShare", OnEvent::closePopup("mKalender"));
		
		echo $F;
	}
	
	public function saveShare(){
		$args = func_get_args();
		
		#$UD = new mUserdata();
		#$UD->getAsArray("shareCalendar");
		
		$i = 0;
		$US = Users::getUsers();
		while($U = $US->getNextEntry()){
			if($U->getID() == Session::currentUser()->getID())
				continue;
			
			if($args[$i] != "none")
				mUserdata::setUserdataS("shareCalendarTo".$U->getID(), $args[$i], "shareCalendar");
			else{
				$UD = new mUserdata();
				$UD->delUserdata("shareCalendarTo".$U->getID());
			}
			
			$i++;
		}
	}
	
	public function saveShowCalendarOf($UserID, $value){
		mUserdata::setUserdataS("showCalendarOf".$UserID, $value);
	}
	
	public function downloadTrashData(){
		$json = file_get_contents("http://www.awv-nordschwaben.de/WebService/AWVService.svc/getData/00000000-0000-0000-0000-000000001190");

		echo "<pre style=\"font-size:10px;max-height:400px;overflow:auto;\">";
		$data = json_decode($json);
		foreach($data->calendar AS $day){
			if($day->fr == "")
				continue;
			
			if($day->dt < date("Ymd"))
				continue;
			
			print_r($day);
			
			
			$tag = Util::parseDate("de_DE", substr($day->dt, 6).".".substr($day->dt, 4, 2).".".substr($day->dt, 0, 4));

			$name = "";
			foreach($day->fr AS $T){
				if($T == "PT")
					$name .= ($name != "" ? ", " : "")."Papiertonne";
				
				if($T == "RM")
					$name .= ($name != "" ? ", " : "")."Restmüll";
				
				if($T == "GS")
					$name .= ($name != "" ? ", " : "")."Gelber Sack";
				
				if($T == "BT")
					$name .= ($name != "" ? ", " : "")."Biotonne";
			}
			
			if($name == "")
				continue;
			
			$F = new Factory("Todo");
			$F->sA("TodoName", $name);
			$F->sA("TodoFromDay", $tag);
			$F->sA("TodoFromTime", "32400");
			$F->sA("TodoTillDay", $tag);
			$F->sA("TodoTillTime", "36000");
			$F->sA("TodoUserID", "-1");
			$F->sA("TodoClass", "Kalender");
			$F->sA("TodoClassID", "-1");
			$F->sA("TodoType", "2");
			$F->sA("TodoRemind", "-1");
			if($F->exists())
				continue;
			
			$F->store();
		}
		echo "</pre>";
	}
}
?>