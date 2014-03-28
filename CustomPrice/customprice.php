<?php
/*
*  2014 
*
*  @author   Xabi Pico <xabikip@gmail.com>
*  @version  Release: 0.1
*  @license  http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)    
*  
*/

if (!defined('_PS_VERSION_'))
	exit;

class Customprice extends Module
{

	public function __construct()
	{
	    $this->name = 'customprice';
	    $this->tab = 'others';
	    $this->version = '0.1';
	    $this->author = 'Xabi Pico';
	    $this->need_instance = 0;
	    $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.5.6.2');
	 
	    parent::__construct();
	 
	    $this->displayName = $this->l('CustomPrice');
	    $this->description = $this->l('Calcula el precio en base a parametros custom');
	 
	    $this->confirmUninstall = $this->l('Esta seguro que desea desinstalar?');
	 
	    if (!Configuration::get('CUSTOM_PRICE'))      
	      $this->warning = $this->l('No name provided');
    }


	public function install()
	{
		return (parent::install() &&
		  $this->registerHook('displayRightColumnProduct') &&
		  $this->registerHook('displayProductTabContent'));
	}

	public function getContent()
	{
	    $output = null;
	 
	    if (Tools::isSubmit('submit'.$this->name))
	    {
	        $precio_carac_nombre = strval(Tools::getValue('precio_carac_nombre'));
	        $precio_carac_num_ad = strval(Tools::getValue('precio_carac_num'));
	        $carac_max = strval(Tools::getValue('carac_max'));
	        $num_max = strval(Tools::getValue('num_max'));
	        if (!$precio_carac_nombre  || empty($precio_carac_nombre) || !Validate::isGenericName($precio_carac_nombre) and
	        	!$precio_carac_num_ad  || empty($precio_carac_num) || !Validate::isGenericName($precio_carac_num_ad) and
	        	!$carac_max  || empty($carac_max) || !Validate::isGenericName($carac_max) and
	        	!$num_max  || empty($num_max) || !Validate::isGenericName($num_max))
	            $output .= $this->displayError( $this->l('Invalid Configuration value') );
	        else
	        {
	            Configuration::updateValue('precio_carac_nombre', $precio_carac_nombre);
	            Configuration::updateValue('precio_carac_num_ad', $precio_carac_num);
	            Configuration::updateValue('carac_max', $carac_max);
	            Configuration::updateValue('num_max', $num_max);
	            $output .= $this->displayConfirmation($this->l('Settings updated'));
	        }
	    }
	    return $output.$this->displayForm();
	}
	
	public function displayForm()
	{
	    // Get default Language
	    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
	     
	    // Init Fields form array
	    $fields_form[0]['form'] = array(
	        'legend' => array(
	            'title' => $this->l('Settings'),
	        ),
	        'input' => array(
	            array(
	                'type' => 'text',
	                'label' => $this->l('Precio por caracter de texto: '),
	                'name' => 'precio_carac_nombre',
	                'suffix' => '€',
	                'size' => 7,
	                'required' => true
	            ),
	            array(
	                'type' => 'text',
	                'label' => $this->l('Precio por caracter numero: '),
	                'name' => 'precio_carac_num',
	                'suffix' => '€',
	                'size' => 7,
	                'required' => true
	            ),
	            array(
	                'type' => 'text',
	                'label' => $this->l('Maximo de caracteres para texto: '),
	                'name' => 'carac_max',
	                'size' => 7,
	                'required' => true
	            ),
	            array(
	                'type' => 'text',
	                'label' => $this->l('Maximo de numeros: '),
	                'name' => 'num_max',
	                'size' => 7,
	                'required' => true
	            )
	        ),
	        'submit' => array(
	            'title' => $this->l('Save'),
	            'class' => 'button'
	        )
	    );
	     
	    $helper = new HelperForm();
	     
	    // Module, token and currentIndex
	    $helper->module = $this;
	    $helper->name_controller = $this->name;
	    $helper->token = Tools::getAdminTokenLite('AdminModules');
	    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
	     
	    // Language
	    $helper->default_form_language = $default_lang;
	    $helper->allow_employee_form_lang = $default_lang;
	     
	    // Title and toolbar
	    $helper->title = $this->displayName;
	    $helper->show_toolbar = true;        // false -> remove toolbar
	    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
	    $helper->submit_action = 'submit'.$this->name;
	    $helper->toolbar_btn = array(
	        'save' =>
	        array(
	            'desc' => $this->l('Save'),
	            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
	            '&token='.Tools::getAdminTokenLite('AdminModules'),
	        ),
	        'back' => array(
	            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
	            'desc' => $this->l('Back to list')
	        )
	    );
	     
	    // Load current value
	    $helper->fields_value['precio_carac_nombre'] = Configuration::get('precio_carac_nombre');
	    $helper->fields_value['precio_carac_num_ad'] = Configuration::get('precio_carac_num');
	    $helper->fields_value['carac_max'] = Configuration::get('carac_max');
	    $helper->fields_value['num_max'] = Configuration::get('num_max');
	     
	    return $helper->generateForm($fields_form);
	}
    

	public function hookdisplayRightColumnProduct($params){

        $this->context->smarty->assign(
	        array(
	            'precio_carac_nombre' => Configuration::get('precio_carac_nombre'),
	            'precio_carac_num_ad' => Configuration::get('precio_carac_num'),
	            'carac_max' => Configuration::get('carac_max'),
	            'num_max' => Configuration::get('num_max')
	        )
        );
        return $this->display(__FILE__, 'customprice.tpl');
		
	}

	
}