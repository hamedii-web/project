<?php

namespace Admin;

use database\DataBase;

class Comment extends Admin{

	public function index(){
		$db = new DataBase();
		$comments = $db->select("SELECT comments.* , posts.title AS post_title , users.username AS username FROM comments LEFT JOIN posts ON comments.post_id = posts.id
		 LEFT JOIN users ON comments.user_id = users.id");
		$unseenComment = $db->select("SELECT * FROM comments WHERE status = ?;" , ['unseen']);
		foreach($unseenComment as $comment){
			$db->update('comments' , $comment['id'] , ['status'] , ['seen']);
		}
		require_once (BASE_PATH . '/template/admin/comments/index.php');
	}

	public function changeStatus($id){
		$db = new DataBase();
		$comment = $db->select("SELECT * FROM comments WHERE `id` = ?;" , [$id])->fetch();

		if(empty($comment)){
			$this->redirectBack();
		}
		if($comment['status'] == 'seen'){
			$db->update('comments' , $id , ['status'] , ['approved']);
		} else{
			$db->update('comments' , $id , ['status'] , ['seen']);
		}

		$this->redirectBack();
	}
}
