<?php

/**
 * Pos class.
 */
class ASLp
{
    /**
     * @var ASDatabase
     */
    private $db = null;

    /**
     * @var ASUser
     */
    private $users;

    /**
     * Class constructor
     * @param ASDatabase $db
     * @param ASUser $users
     */
    public function __construct(ASDatabase $db, ASUser $users)
    {
        $this->db = $db;
        $this->users = $users;
    }

	public function getcategorydelete($id) {
		$sql = $this->db->delete("pos_category", "category_id = :el", array( "el" => $id ));
		return $sql;
	}
	

}
