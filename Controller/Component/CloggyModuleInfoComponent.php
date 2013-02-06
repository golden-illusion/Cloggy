<?php

App::uses('Component', 'Controller');

/**
 * Manage modules including their info.ini
 * 
 * @author hiraq
 * @name CloggyModuleInfoComponent
 * @package Cloggy
 * @subpackage Component
 */
class CloggyModuleInfoComponent extends Component {
    
    /**
     * Cloggy acl component
     * @var CloggyAclComponent 
     */
    public $components = array('Cloggy.CloggyAcl');

    /**
     * Store registered modules
     * 
     * @access private
     * @var array
     */
    private $__modules = array();

    /**
     * Setup and get all registered modules
     * @access public
     */
    public function modules() {
        $modules = Configure::read('Cloggy.modules');
        if (!empty($modules) && is_array($modules)) {
            foreach ($modules as $module) {
                
                //check if module allowed or not
                $checkModuleAllowed = $this->CloggyAcl->isModuleAllowedByAro($module);
                
                /*
                 * only list allowed modules
                 */
                if($checkModuleAllowed) {
                    if (!array_key_exists($module, $this->__modules)) {
                        
                        $this->__configureModuleInfo($module);
                        $this->__modules[$module]['name'] = $this->getModuleName();
                        $this->__modules[$module]['desc'] = $this->getModuleDesc();
                        $this->__modules[$module]['author'] = $this->getModuleAuthor();
                        $this->__modules[$module]['url'] = $this->getModuleUrl();
                        $this->__modules[$module]['dep'] = $this->getModuleDependency();
                        
                    }
                }
                
            }
        }
    }

    /**
     * Get all modules
     * @access public
     */
    public function getModules() {
        return $this->__modules;
    }

    /**
     * Check if module exists or not
     * @access public
     * @param string $module
     * @return boolean
     */
    public function isModuleExists($module) {
        return array_key_exists($module, $this->__modules);
    }        

    /**
     * Get module info
     * @access public
     * @param string $module
     * @return null|array
     */
    public function getModuleInfo($module) {
        if (array_key_exists($module, $this->__modules)) {
            return $this->__modules[$module];
        } else {
            return null;
        }
    }

    /**
     * Get module name
     * @access public
     * @return string
     */
    public function getModuleName() {
        $name = Configure::read('info.name');
        return $name;
    }

    /**
     * Get module description
     * @access public
     * @return string
     */
    public function getModuleDesc() {
        $desc = Configure::read('info.desc');
        return $desc;
    }

    /**
     * Get module author
     * @access public
     * @return string
     */
    public function getModuleAuthor() {
        $author = Configure::read('info.author');
        return $author;
    }

    /**
     * Get module url
     * @access public
     * @return string
     */
    public function getModuleUrl() {
        $url = Configure::read('info.url');
        return $url;
    }

    /**
     * Get module dependency
     * @access public
     * @return string 
     */
    public function getModuleDependency() {
        $dep = Configure::read('info.dependency');
        return $dep;
    }

    /**
     * Load info.ini for requested module
     * @access private
     * @param string $moduleName
     */
    private function __configureModuleInfo($moduleName) {
        App::uses('IniReader', 'Configure');
        Configure::config('ini', new IniReader(CLOGGY_PATH_MODULE . $moduleName . DS));
        Configure::load('info.ini', 'ini');
    }

}