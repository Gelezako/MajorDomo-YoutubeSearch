<?php
/**
* Поиск по Youtube 
* @package project
* @author Alex Sokolov <admin@gelezako.com>
* @copyright http://blog.gelezako.com (c)
* @version 0.1 (wizard, 11:03:33 [Mar 18, 2017])
*/
//
//
class SearchYoutube extends module {
/**
* SearchYoutube
*
* Module class constructor
*
* @access private
*/
function SearchYoutube() {
  $this->name="SearchYoutube";
  $this->title="Поиск по Youtube";
  $this->module_category="<#LANG_SECTION_APPLICATIONS#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }
}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;
}
/**
* BackEnd
*
* Module backend
*
* @access public
*/

function admin(&$out) {
	 $this->getConfig();
	 $out['API_KEY']=$this->config['API_KEY'];
	 $out['maxCount']=$this->config['maxCount'];
	 if ($this->view_mode=='update_settings') {
	   global $api_key,$maxCount;
	   $this->config['API_KEY']=$api_key;
	   $this->config['maxCount']=$maxCount;
	   sg('Youtube.key',$api_key);
	   sg('Youtube.maxCount',$maxCount);
	   sg('Youtube.next',(int)$maxCount-1);
	   $this->saveConfig();
	   $this->redirect("?");
	 }
	 

}
/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
	     $className = 'SearchVideo'; //имя класса
		 $objectName = array('Youtube');//имя обьектов
		 $objDescription = array('Поиск по Youtube');
		 $rec = SQLSelectOne("SELECT ID FROM classes WHERE TITLE LIKE '" . DBSafe($className) . "'");
		 
			if (!$rec['ID']) {
				$rec = array();
				$rec['TITLE'] = $className;
				$rec['DESCRIPTION'] = $objDescription;
				$rec['ID'] = SQLInsert('classes', $rec);
			}
			for ($i = 0; $i < count($objectName); $i++) {
				$obj_rec = SQLSelectOne("SELECT ID FROM objects WHERE CLASS_ID='" . $rec['ID'] . "' AND TITLE LIKE '" . DBSafe($objectName[$i]) . "'");
				if (!$obj_rec['ID']) {
					$obj_rec = array();
					$obj_rec['CLASS_ID'] = $rec['ID'];
					$obj_rec['TITLE'] = $objectName[$i];
					$obj_rec['DESCRIPTION'] = $objDescription[$i];
					$obj_rec['ID'] = SQLInsert('objects', $obj_rec);
				}
			}
			addClassProperty('Youtube', 'key', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			
			addClassProperty('Youtube', 'q', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			addClassProperty('Youtube', 'maxCount', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			addClassProperty('Youtube', 'next', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			
			addClassProperty('Youtube', 'title1', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			addClassProperty('Youtube', 'title2', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			addClassProperty('Youtube', 'title3', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			addClassProperty('Youtube', 'title4', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			addClassProperty('Youtube', 'title5', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			
			addClassProperty('Youtube', 'videoId1', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			addClassProperty('Youtube', 'videoId2', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			addClassProperty('Youtube', 'videoId3', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			addClassProperty('Youtube', 'videoId4', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			addClassProperty('Youtube', 'videoId5', 'include_once(DIR_MODULES."SearchYoutube/SearchYoutube.class.php");');
			
		  parent::install();
 }
 
 
  public function uninstall()
   {
      SQLExec("delete from pvalues where property_id in (select id FROM properties where object_id in (select id from objects where class_id = (select id from classes where title = 'SearchVideo')))");
      SQLExec("delete from properties where object_id in (select id from objects where class_id = (select id from classes where title = 'SearchVideo'))");
      SQLExec("delete from objects where class_id = (select id from classes where title = 'SearchVideo')");
      SQLExec("delete from classes where title = 'SearchVideo'");
      
      parent::uninstall();
   }
 
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgTWFyIDE4LCAyMDE3IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/
