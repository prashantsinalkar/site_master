<?php
namespace SiteMaster\Registry\Sites;

use DB\RecordList;
use SiteMaster\InvalidArgumentException;

class ApprovedForUser extends RecordList
{
    public function __construct(array $options = array())
    {
        if (!isset($options['user_id'])) {
            throw new InvalidArgumentException('A user_id must be set', 500);
        }
        
        $options['array'] = self::getBySQL(array(
            'sql'         => $this->getSQL($options['user_id']),
            'returnArray' => true
        ));

        parent::__construct($options);
    }

    public function getDefaultOptions()
    {
        $options = array();
        $options['itemClass'] = '\SiteMaster\Registry\Site';
        $options['listClass'] = __CLASS__;

        return $options;
    }

    public function getSQL($user_id)
    {
        //Build the list
        $sql = "SELECT sites.id
                FROM sites
                LEFT JOIN site_members ON (site_members.sites_id = sites.id)
                WHERE site_members.users_id = " . (int)$user_id ."
                    AND site_members.status = 'APPROVED'";

        return $sql;
    }
}