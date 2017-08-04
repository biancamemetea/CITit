<?php
/**
 * DotBoost Technologies Inc.
 * DotKernel Application Framework
 *
 * @category   DotKernel
 * @copyright  Copyright (c) 2009-2015 DotBoost Technologies Inc. (http://www.dotboost.com)
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    $Id: User.php 981 2015-06-11 13:51:41Z gabi $
 */

/**
 * User Model
 * Here are all the actions related to the user
 * @category   DotKernel
 * @package    Frontend
 * @author     DotKernel Team <team@dotkernel.com>
 */

class Article extends Dot_Model_User
{
	
	private $_userAgent;
	private $_httpReferer;
	
	/**
	 * Constructor
	 * @access public
	 */
	public function __construct($userAgent = null, $httpReferer=null)
	{
		parent::__construct();
		// if no userAgent is given on function call mark it as empty - if the userAgent is empty keep it empty
		// if the userAgent stays empty it can be used for robot detecting or devices with blank UA (usually bots)
		// HTTP Reffer is optional so mark it empty if there is no HTTP Reffer
		$this->_userAgent = (!is_null($userAgent)) ? $userAgent : '';
		$this->_httpReferer = (!is_null($httpReferer)) ? $httpReferer : '';
	}

	/**
	 * Get user info
	 * @access public
	 * @param int $id
	 * @return array
	 */
	public function getAllArticleData()
	{
		$select = $this->db->select()
						->from('article');
		return $this->db->fetchAll($select);
	}
	public function getSingleArticleData($id)
	{
		$select = $this->db->select()
						->from('article')
						->where('id = ?', $id);
		return $this->db->fetchRow($select);
	}
	public function getCommentByArticleId($id)
	{
		$comepletedData = [];
		$comments = $this->getComments($id);

		foreach ($comments as $key => $value) {
			$replies = $this->getCommentReplytByCommentId($value['id']);
			$comepletedData[$value['id']]['content'] = $value['content'];
			$comepletedData[$value['id']]['username'] = $value['username'];
			if(isset($replies) && !empty($replies))
			{
				$comepletedData[$value['id']]['replies'] = $replies;
			}
		}
		return $comepletedData;
	}


	/**
	* get comments that have parrent id 0
	*/
	public function getComments($id)
	{
		$defaultParentId = 0;
	    $select = $this->db->select()
	                    ->from('comment')
	                    ->where('postId = ?', $id)
	                    ->where('parent = ?', $defaultParentId)
	                    ->join('user','user.id = comment.userId','user.username');
	    $result = $this->db->fetchAll($select);
	    
	    return $result;
	}

	/**
	* get coment reply by coment id
	*/
	public function getCommentReplytByCommentId($id)
	{
		$select = $this->db->select()
	                    ->from('comment',array('content','date'))
	                    ->where('parent = ?', $id)
	                    ->join('user','user.id = comment.userId','username');
	    $result = $this->db->fetchAll($select);
	    return $result;          
	}

	public function deleteCommentById($id)
    {
        $this->db->delete('comment', "id = " . $id);
    }
}