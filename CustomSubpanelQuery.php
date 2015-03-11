<?php
class RelatedContactsSubpanel extends Link2
{
    protected $db;
	/*
	*/	
    public function __construct($linkName, $bean, $linkDef = false)
    {
        $this->focus = $bean;
        $this->name = $linkName;
        $this->db = DBManagerFactory::getInstance();
        if (empty($linkDef)) 
        {
        	$this->def = $bean->field_defs[$linkName];
        } 
        else 
        {
            $this->def = $linkDef;
        }
    } 
    /*  
    */
    public function loadedSuccesfully()
    {
        return true;
    }
    /* 
    */
    /*
	public function getRelatedModuleName()
	{
        return 'Contacts';
    }
	*/
	/*
	*/	
    public function buildJoinSugarQuery($sugar_query, $options = array())
    {
        $joinParams = array('joinType' => isset($options['joinType']) ? $options['joinType'] : 'INNER');
        $jta = 'related_name_cstm';
        if (!empty($options['joinTableAlias'])) 
        {
            $jta = $joinParams['alias'] = $options['joinTableAlias'];
        }

        $sugar_query->joinRaw($this->getCustomJoin($options), $joinParams);
        return $sugar_query->join[$jta];
    }
    /* 
    */
    protected function getCustomJoin($params = array())
    {
        $bean_id = $this->db->quoted($this->focus->id);
        $person_id = $this->db->quoted($this->focus->person_id_c);
        
        $sql = " INNER JOIN(";
        $sql .= "SELECT id_c FROM contacts_cstm WHERE id_c !={$bean_id} and person_id_c={$person_id}";
        $sql .= ") contacts_result ON contacts_result.id_c = sugar_query_table.id";
        return $sql;
    }
}

?>