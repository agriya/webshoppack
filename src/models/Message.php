<?php namespace Agriya\Webshoppack;

/**
 *
 *
 * @version $Id$
 * @copyright 2013
 */
class Message extends CustomEloquent
{
    protected $table = "message";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "date_added", "from_user_id", "to_user_id", "last_replied_by", "last_replied_date", "subject", "reply_count", "message_text", "is_deleted", "is_replied", "rel_type", "rel_id", "rel_table");

    public function addNew($data_arr)
	{
		$this->setFieldValues($data_arr);
		$this->save();
		return $this->id;
	}
}
