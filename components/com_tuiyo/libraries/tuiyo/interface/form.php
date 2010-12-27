<?php
/**
 * ******************************************************************
 * Form object for the Tuiyo platform                               *
 * ******************************************************************
 * @copyright : 2008 tuiyo Platform                                 *
 * @license   : http://platform.tuiyo.com/license   BSD License     * 
 * @version   : Release: $Id$                                       * 
 * @link      : http://platform.tuiyo.com/                          * 
 * @author 	  : livingstone[at]drstonyhills[dot]com                 * 
 * @access 	  : Public                                              *
 * @since     : 1.0.0 alpha                                         *   
 * @package   : tuiyo                                               *
 * ******************************************************************
 **/
 
 defined('TUIYO_EXECUTE') || die;
 
 /**
  * TuiyoForm
  * 
  * @package tuiyo
  * @version $Id$
  * @access public
  */
 class TuiyoForm {
 	
 	var $formId 	= null;
 	
 	var $formMethod = "post";
 	
 	var $elements 	= array();
 	
 	/**
 	 * TuiyoForm::__construct()
 	 * 
 	 * @return void
 	 */
 	public function __construct($formId = null){
 		$this->formId 	= $formId;
 		$this->elements[] = array( 
		 	"tagName"=>"input", 
			 "type"=>"hidden", 
			 "name"=>JUtility::getToken(), 
			 "value"=>"1" 
		 ) ;
 	}
 	
 	
 	/**
 	 * TuiyoForm::text()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $type
 	 * @param mixed $label
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function text($name, $attr = array()){
 		//return input element of type Password!
 		$attr  = (array)$attr;
 		$label = (empty($attr["label"])) ? $name : $attr["label"];
 		$descr = (empty($attr["description"])) ? NULL : $attr["description"];
 		
 		unset($attr["description"]);
 		unset($attr["label"]); 	
		 	
 		$html  = (array)$this->getHTMLRow("input",	$label, $descr );
	    //More Attributes
		$moreAttr = array( "class"=>"TuiyoFormText","type"=>"text"  );
	    foreach($attr as $attName=>$attValue ){
	    	$moreAttr[$attName]= $attValue	;
	    }
	    
	    //Return Form Row
			$html["childNodes"][1]["childNodes"] = array( array_merge( $html["childNodes"][1]["childNodes"][0] , $moreAttr ) );
		return $html;
 	}
 	
 	/**
 	 * TuiyoForm::password()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function password($name, $attr = array( )){
 		//return input element of type Password!
 		$attr  = (array)$attr;
 		$label = (empty($attr["label"])) ? $name : $attr["label"];
 		$descr = (empty($attr["description"])) ? NULL : $attr["description"];
 		
 		unset($attr["description"]);
 		unset($attr["label"]); 		
 		$html  = (array)$this->getHTMLRow("input",  $label , $descr );
	    //More Attributes
		$moreAttr = array( "class"=>"TuiyoFormText", "type"=>"password" );
	    foreach($attr as $attName=>$attValue ){
	    	$moreAttr[$attName]= $attValue	;
	    }
	    
	    //Return Form Row
			$html["childNodes"][1]["childNodes"] = array( array_merge( $html["childNodes"][1]["childNodes"][0] , $moreAttr ) );
		return $html;
 	}
 	
 	/**
 	 * TuiyoForm::getHTMLRow()
 	 * @param mixed $tagName
 	 * @param mixed $type
 	 * @param mixed $title
 	 * @return
 	 */
 	private function getHTMLRow($tagName, $title = NULL, $descr = NULL, $isButton = FALSE ){
 		
		 $title = ucfirst( $title );
 		 $tip   = empty($descr) ? null : strtolower( $descr );
 		 $isBtn = ($isButton)? "isButton" : NULL ;
		 $row  	= array(
 			"tagName" 	=>"div",
 			"class"		=>"tuiyoTableRow",
		    "childNodes"=>array(
		    	array( "tagName"=>"div", "class"=>"tuiyoTableCell hasTip $isBtn", "style"=>"width: 35% ","innerHTML"=>$title, "title"=>$tip ),
				array(
					"tagName"	=>"div",
					"style"		=>(!empty($tip))? "width: 65%;" : "width: 65%;" ,
					"class"		=>"tuiyoTableCell",
					"childNodes"=>array(
						array("tagName"=>$tagName, "title"=>$tip ),
						array("tagName"=>"i", "style"=>"padding-left: 35%; font-size: 11px; margin-bottom: 4px", "innerHTML"=>$tip )					
					)
				),					
				array( "tagName" =>"div", "class"=>"tuiyoClearFloat", "style" =>(!empty($tip))? "margin-bottom: 6px;" : null  )
			) 
	    );
	    
	    if(empty($tip)){ unset($row["childNodes"][2]);}

		return (array)$row;
 	}
 	
 	
 	/**
 	 * TuiyoForm::getRawHTMLRow()
 	 * Returns raw form element row
 	 * @param mixed $tagName
 	 * @param mixed $title
 	 * @param mixed $descr
 	 * @return
 	 */
 	private function getRawHTMLRow($tagName, $title = NULL, $descr = NULL ){
	 	
		 $title 		= ucfirst( $title );
 		 $tip   		= empty( $descr ) ? null : strtolower( $descr );
		 $row  			= new stdClass ;
		 $row->tagName 	= $tagName;
	 	 $row->title	= $tip;
	  
		return (object)$row;
 	}
 	
 	/**
 	 * TuiyoForm::textarea()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function textarea($name, $attr=array()){

 		$attr  = (array)$attr;
 		$label = (empty($attr["label"])) ? $name : $attr["label"];
 		$descr = (empty($attr["description"])) ? NULL : $attr["description"];
 		
 		unset($attr["description"]);
 		unset($attr["label"]);
 		
 		$html  = (array)$this->getHTMLRow("textarea", $label , $descr );
	    //More Attributes
		$moreAttr = array( "class"=>"TuiyoFormTextArea"  );
	    foreach($attr as $attName=>$attValue ){
	    	$moreAttr[$attName]= $attValue	;
	    }
	    
	    //Return Form Row
		$html["childNodes"][1]["childNodes"] = array( array_merge( $html["childNodes"][1]["childNodes"][0] , $moreAttr ) );
		
		return $html; 		
 	}
 	
 	/**
 	 * TuiyoForm::button()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function button($name, $attr=array()){
 		
 		$attr  = (array)$attr;
 		$descr = (empty($attr["description"])) ? NULL : $attr["description"];
 		
 		unset($attr["description"]);

 		$html  = (array)$this->getHTMLRow("button", "Submit" , $descr , TRUE );
 		
	    //More Attributes
		$moreAttr = array( "class"=>"TuiyoFormButton", "style" => "" );
	    foreach($attr as $attName=>$attValue ){
	    	$moreAttr[$attName]= $attValue	;
	    }
	    
	    //Return Form Row
		$html["childNodes"][1]["childNodes"] = array( array_merge( $html["childNodes"][1]["childNodes"][0] , $moreAttr ) );
		return $html;		
 	}
 	
 	/**
 	 * TuiyoForm::file()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @param bool $tuiyoUpload, if uploads handled by Tuiyo
 	 * @return void
 	 */
 	public function file($name, $attr=array(), $tuiyoUpload = TRUE ){
 		//return input element of type File Type!
 		$attr  = (array)$attr;
 		$label = (empty($attr["label"])) ? $name : $attr["label"];
 		$descr = (empty($attr["description"])) ? NULL : $attr["description"];
 		
 		unset($attr["description"]);
 		unset($attr["label"]); 
		 		
 		$html  = (array)$this->getHTMLRow("input",	$label , $descr );
	    //More Attributes
		$moreAttr = array( "class"=>"TuiyoFormFile" , "type"=>"file" );
	    foreach($attr as $attName=>$attValue ){
	    	$moreAttr[$attName]= $attValue	;
	    }
	    
	    //Return Form Row
		$html["childNodes"][1]["childNodes"] = array( array_merge( $html["childNodes"][1]["childNodes"][0] , $moreAttr ) );
		return $html; 		
 	}
 	
 	/**
 	 * TuiyoForm::image()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function image($name, $attr=array()){}
 	
 	/**
 	 * TuiyoForm::hidden()
 	 * A hidden form elements
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function hidden($name, $attr=array()){
 		//return input element of type Password!
 		$attr  = (array)$attr;
 		$label = (empty($attr["label"])) ? $name : $attr["label"];
 		
 		unset($attr["label"]);
 		
 		$html  		= $this->getRawHTMLRow("input", "" );
		$html->class= "TuiyoFormText";
		$html->type = "hidden";
		
		foreach($attr as $attribute=>$value):
			//attribute value pair
			$html->$attribute = $value; 
		
		endforeach;
		
	    
	    //Return Form Row
		return $html;
		
 	}
 	
  	/**
 	 * TuiyoForm::radioGroup()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function radioGroup($name, $attr=array() ){
 		//return input element of type Password!
 		$attr    = (array)$attr;
 		$label   = (empty($attr["label"])) ? $name : $attr["label"];
 		$descr   = (empty($attr["description"])) ? NULL : $attr["description"];
 		$options = (empty($attr["options"])) ? NULL : $attr["options"];
 		
 		unset($attr["label"] );
	    unset($attr["options"] );  
 		unset($attr["description"]); 	
		 	
 		$html  = (array)$this->getHTMLRow("div", $label );
	    //More Attributes
		$moreAttr = array( 
			"class" =>"TuiyoFormRadioGroup" ,
			"style"  => "margin-bottom: 8px",
			"childNodes" => array( )  
		);
		
	    foreach($options as $n=>$v ){
	    	$moreAttr["childNodes"][] = array(
				"tagName"=>"input",
				"type"	 =>"radio",
				"style"  => "margin: 4px",
				"title"	 =>$descr,
				"name"	 =>$name,
				"class"  =>"TuiyoFormRadio",
				"value"	 =>$v,
				"checked"=>((int)$v === (int)$attr["value"])? "checked" : false
			);
			$moreAttr["childNodes"][] = array("tagName"=>"label", "innerHTML"=>$n) ;
	    }
	    
	    //Return Form Row
		$html["childNodes"][1]["childNodes"] = array( array_merge( $html["childNodes"][1]["childNodes"][0] , $moreAttr ) );
		return $html; 		
 	}
 	
	 /**
 	 * TuiyoForm::checkboxGroup()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function checkboxGroup($name, $attr=array(), $options=array() ){} 	
 	
 	/**
 	 * TuiyoForm::select()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function select($name, $attr=array() ){
 		//return input element of type Password!
 		$attr    = (array)$attr;
 		$label   = (empty($attr["label"])) ? $name : $attr["label"];
 		$descr   = (empty($attr["description"])) ? NULL : $attr["description"];
 		$options = (empty($attr["options"])) ? NULL : $attr["options"];
 		
 		unset($attr["label"] );
	    unset($attr["options"] );  
 		unset($attr["description"]); 	
		 	
 		$html  = (array)$this->getHTMLRow("select", $label );
	    //More Attributes
		$moreAttr = array( 
			"class" =>"TuiyoFormDropDown" ,
			"name"	=> $name,
			"title"	=> $descr,
			"childNodes" => array( )  
		);
		
	    foreach($options as $n=>$v ){
	    	$moreAttr["childNodes"][] = array(
				"tagName"=>"option",
				"class"  =>"TuiyoFormSelectOption",
				"value"	 =>$v,
				"innerHTML"=>$n,
				"selected"=>($v === $attr["value"])? "selected" : false
			);
	    }
	    
	    //Return Form Row
		$html["childNodes"][1]["childNodes"] = array( array_merge( $html["childNodes"][1]["childNodes"][0] , $moreAttr ) );
		return $html;  		
 	}
 	
 	
 	/**
 	 * TuiyoForm::list()
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return
 	 */
 	public function droplist($name, $attr = array() ){
 		return $this->select($name, $attr );
 	}

  	/**
 	 * TuiyoForm::fieldset()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function fieldset($name, $attr=array()){}
 	
 	
 	/**
 	 * TuiyoForm::date()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $attr
 	 * @return void
 	 */
 	public function date($name, $attr=array()){}
	 	
 	/**
 	 * Gets a complete Form, for all elements passed
 	 * TuiyoForm::getForm()
 	 * 
 	 * @param mixed $formId
 	 * @return void
 	 */
 	public function outPutForm( $formId = NULL, $front = FALSE , $submit = TRUE ){
 		
		$token 	= &JUtility::getToken();
		$user 	= TuiyoAPI::get("user", null);
 		
 		//Add The form Button
 		array_push( 
		 	$this->elements, 
		 	$this->hidden("option", 	array("type"=>"hidden", "name"=>"option", 	"value"=>"com_tuiyo") ), 
 			$this->hidden($token, 		array("type"=>"hidden", "name"=>$token, 	"value"=>"1") ) , 
		 	$this->hidden("option", 	array("type"=>"hidden", "name"=>"option", 	"value"=>"com_tuiyo") ) , 
 			$this->hidden("do", 		array("type"=>"hidden", "name"=>"do", 		"value"=>"saveParams") ) , 
 			$this->hidden("userid", 	array("type"=>"hidden", "name"=>"userid", 	"value"=>"".$user->id."")  ) , 
 			$this->hidden("context", 	array("type"=>"hidden", "name"=>"context", 	"value"=>"systemTools") ) 
  		);
  		//Will you like to add a submit Button?
  		if($submit){
  			array_push(
		  		$this->elements,
		  		$this->button("submit", 	array("type"=>"submit", "innerHTML"=>"Save Configuration Data") ) 
		  	);
  		}
		//Are we on the frontpate
		if($front){
			array_push( $this->elements, $this->hidden("view", 	array("type"=>"hidden", "name"=>"view", 	"value"=>"profile") ) ) ;
		}	    
	    //Add form Tags and elements
		$form = array(
 			"tagName" 	=> "form",
 			"action"	=> "index.php",
	        "method"	=> !empty($this->formMethod)? "post":$this->formMethod,
			"id"		=> !empty($formId )? $formId  : $this->formId,
			"name"		=> !empty($formId )? $formId  : $this->formId,		
 			"class"		=> "tuiyoTable TuiyoForm",
		    "childNodes"=> (array)$this->elements
	    );
	    
	    //print_R( $this->elements );
	    
	    return $form;
 	}
 	
 	public function outPutFormLite( $formID = NULL )
	 {
		$form = array(
 			"tagName" 	=> "form",
 			"action"	=> "index.php",
	        "method"	=> !empty($this->formMethod)? "post":$this->formMethod,
			"id"		=> !empty($formId )? $formId  : $this->formId,
			"name"		=> !empty($formId )? $formId  : $this->formId,		
 			"class"		=> "tuiyoTable TuiyoForm",
		    "childNodes"=> (array)$this->elements
	    );
	    
	    //print_R( $this->elements );
	    
	    return $form;	 	
	 }
 	
 	
 	/**
 	 * TuiyoForm::add()
 	 * @param mixed $elementType
 	 * @param mixed $args
 	 * @return void
 	 */
 	public function add($elementType, $name, $attr = NULL ){
 		
	    $attr 	= (array)$attr;
 		$self 	= &$this;
 		
 		if(!method_exists($self, $elementType)){
 			return false;
 		}
 		$thisElement = call_user_func(array($self , $elementType), $name, $attr );
 		
	  	array_push( $this->elements, $thisElement );
	  	
	  	return true;	
 	}
 	
 	/**
 	 * Gets a new Instance of the Form Object
 	 * TuiyoForm::getInstance()
 	 * 
 	 * @param mixed $formid
 	 * @param bool $ifNotExist
 	 * @return
 	 */
 	public function getInstance($formid = NULL, $ifNotExist = TRUE){
		static $instance = array();
		//Generate a random Form key if empty
		$formid	= (empty($formid) )? TuiyoAPI::random() : $formid ;
		
		if(isset($instance['fid:'.$formid])&&$ifNotExist){
			if(is_object($instance['fid:'.$formid])){
				return $instance['fid:'.$formid];
			}else{
				unset($instance['fid:'.$formid]);
				TuiyoForm::getInstance($formid , $ifNotExist );			
			}								
		}else{
			$instance['fid:'.$formid] = new TuiyoForm( $formid )	;	
		}
		return $instance['fid:'.$formid];	
 	}
 	

 	
 }