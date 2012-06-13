<?php

/**
 * HOW TO :
 *
 * 1. In the form class :
 *
 * {name} = key from $_manyDependentTable
 *
 * $this->removeElement({name});
 * $subForm = new Magazine_Form_Model_Admin_ManyToManyWithOrder(array(
 *     'name'              => {name},
 *     'title'             => {label},
 *     'description'       => {description}
 * ));
 * $this->addSubForm($subForm, {name});
 *
 *
 * 2. In the controller
 *
 * Create a action named jsonAutocompleteAction for the search field
 * This action must return a json with the following syntax (jQuery autocomplete) :
 * [{"id":"1","label":"Label 1"},{"id":"2","label":"Label 2"}]
 *
 *
 * @TODO : Finish this howto
 */
class Magazine_Form_Model_Admin_ManyToManyWithOrder extends Centurion_Form
{
    /**
     * Default form decorators.
     *
     * @var array
     */
    public $defaultFormDecorators = array(
        array('ViewScript', array('viewScript' => '_element/_many.phtml', 'class' => 'form', 'placement' => ''))
    );


    /**
     * Name of the form & the multiselect element
     * Use a key from $_manyDependentTables
     *
     * @var null
     */
    protected $_elementName = null;

    /**
     * Model of the parent form
     *
     * @var null
     */
    protected $_parentModel = null;

    /**
     * @var null|Zend_Db_Table_Rowset_Abstract
     */
    protected $_articleAllowed = null;


    /**
     * @return {string}
     */
    public function getElementName()
    {
        return $this->_elementName;
    }


    /**
     * @return {string}
     */
    public function getArticleAllowed()
    {
        return $this->_articleAllowed;
    }


    /**
     * Constructor
     *
     * Create a multiselect element with the same name than the form
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);

        $this->_elementName = $options['name'];
        $this->_parentModel = $options['model'];

        $manyDependentTables = $this->_parentModel->info(Centurion_Db_Table_Abstract::MANY_DEPENDENT_TABLES);
        $tableName = $manyDependentTables[$this->_elementName]['refTableClass'];

        $this->_articleAllowed = Centurion_Db::getSingletonByClassName($tableName)->fetchAll();
        $this->_articleAllowed = array();

        $multiOptions = array();
        foreach ($this->_articleAllowed as $row){
            $multiOptions[$row->id] = $row->title;
        }
        
        $this->addElement('multiSelect', $this->_elementName, array(
            'label'        => $options['title'],
            'description'  => $options['description'], 
            'multiOptions' => $multiOptions
        ));
    }


    /**
     * Applies default form decorators
     *
     * @return void
     */
    public function postCleanAsSubform()
    {
        $this->setDecorators($this->defaultFormDecorators);
    }


    /**
     * @param $valuesSelected
     */
    public function setValue($valuesSelected)
    {
        $this->getElement($this->_elementName)->setValue($valuesSelected);
    }

}
