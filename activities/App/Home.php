<?php

namespace App;

use database\DataBase;

class Home
{
    public function index()
    {
        $db = new DataBase();
        $setting = $db->select("SELECT * FROM setting")->fetch();
        $menus = $db->select("SELECT * FROM menus WHERE parent_id IS NULL")->fetchAll();

        $topPostView = $db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.id = posts.id)
         AS comment_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, 
         (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE posts.selected = 1
         ORDER BY created_at DESC LIMIT 0,3')->fetchAll();

        $brakingNews = $db->select('SELECT * FROM posts WHERE breaking_news = 1')->fetch();


        $lastPosts = $db->select("SELECT posts.* , (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category,
        (SELECT COUNT(*) FROM comments WHERE comments.id = posts.id) AS comment_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username
        FROM posts ORDER BY created_at DESC LIMIT 0,6")->fetchAll();

        $bodyBanner = $db->select("SELECT * FROM banners LIMIT 0,1")->fetch();
        $sidebarBanner = $db->select("SELECT * FROM banners LIMIT 0,1")->fetch();

        $popularPosts = $db->select("SELECT posts.* , (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category ,
        (SELECT COUNT(*) FROM comments WHERE comments.id = posts.id) AS comment_count , (SELECT username FROM users WHERE users.id = posts.user_id) AS username
         FROM posts ORDER BY view DESC LIMIT 0,3")->fetchAll();


        $mostCommentPosts = $db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.id = posts.id)
         AS comment_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, 
         (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE posts.selected = 1
         ORDER BY comment_count DESC LIMIT 0,4')->fetchAll();


        require_once(BASE_PATH . '/template/app/index.php');
    }

    protected function redirectBack()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    public function show($id)
    {
        $db = new DataBase();
        $setting = $db->select("SELECT * FROM setting")->fetch();
        $menus = $db->select("SELECT * FROM menus WHERE parent_id IS NULL")->fetchAll();

        $mostCommentPosts = $db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.id = posts.id)
         AS comment_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, 
         (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE posts.selected = 1
         ORDER BY comment_count DESC LIMIT 0,4')->fetchAll();


        $popularPosts = $db->select("SELECT posts.* , (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category ,
         (SELECT COUNT(*) FROM comments WHERE comments.id = posts.id) AS comment_count , (SELECT username FROM users WHERE users.id = posts.user_id) AS username
          FROM posts ORDER BY view DESC LIMIT 0,3")->fetchAll();

        $topPostView = $db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.id = posts.id)
         AS comment_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, 
         (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE posts.selected = 1
         ORDER BY created_at DESC LIMIT 0,3')->fetchAll();

        $post = $db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.id = posts.id)
        AS comment_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, 
        (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE posts.id = ?' , [$id])->fetch();

        $comments = $db->select("SELECT * , (SELECT username FROM users WHERE users.id = comments.user_id) AS username
        FROM comments WHERE post_id = ? AND status = 'approved'" , [$id])->fetchAll();
        require_once(BASE_PATH . '/template/app/show.php');
    }

    public function commentStore($request , $post_id)
    {
        if(isset($_SESSION['user']))
        {
            if($_SESSION['user'] != null)
            {
                $db = new DataBase();
                $db->insert('comments' , ['user_id' , 'post_id' , 'comment'] , [$_SESSION['user'] , $post_id , $request['comment']]);
                $this->redirectBack();
            }
            else
            {
                $this->redirectBack();
            }
        }
        else
        {
            $this->redirectBack();
        }
    }

    public function category($id)
    {
        $db = new DataBase();
        $setting = $db->select("SELECT * FROM setting")->fetch();
        $menus = $db->select("SELECT * FROM menus WHERE parent_id IS NULL")->fetchAll();
        $mostCommentPosts = $db->select('SELECT posts.*, (SELECT COUNT(*) FROM comments WHERE comments.id = posts.id) AS comment_count, (SELECT username FROM users WHERE users.id = posts.user_id) AS username, (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE posts.selected = 1 ORDER BY comment_count DESC LIMIT 0,4')->fetchAll();
        $brakingNews = $db->select('SELECT * FROM posts WHERE breaking_news = 1')->fetch();
        $bodyBanner = $db->select("SELECT * FROM banners LIMIT 0,1")->fetch();
        $sidebarBanner = $db->select("SELECT * FROM banners LIMIT 0,1")->fetch();
        $popularPosts = $db->select("SELECT posts.* , (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category ,(SELECT COUNT(*) FROM comments WHERE comments.id = posts.id) AS comment_count , (SELECT username FROM users WHERE users.id = posts.user_id) AS username FROM posts ORDER BY view DESC LIMIT 0,3")->fetchAll();
        $category = $db->select("SELECT * FROM categories WHERE id = ? " , [$id])->fetch();
        $categoryPosts = $db->select("SELECT posts.* , (SELECT username FROM users WHERE users.id = posts.user_id) AS username ,(SELECT COUNT(*) FROM comments WHERE comments.id = posts.id) AS comment_count , (SELECT name FROM categories WHERE categories.id = posts.cat_id) AS category FROM posts WHERE cat_id = ? ORDER BY created_at DESC LIMIT 0,6" , [$id])->fetchAll();


        require_once(BASE_PATH . '/template/app/category.php');

    }
}
