<?php

/**
 * Active Record Test Class
 * @link http://www.yiiframework.com/wiki/428/drills-search-by-a-has_many-relation/
 */
class ActiveRecordTestController extends Controller
{

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionGetPostWithUserById($id)
	{
		$post = Post::model()->findByPk($id);
		echo "Title : " . $post->title . "<br />";
		echo "Author : " . $post->author->username . "<br />";
		echo "Content : " . $post->content;

		// SELECT * FROM `tbl_post` `t` WHERE `t`.`id`=10 LIMIT 1
		// SELECT `author`.`id` AS `t1_c0`, `author`.`username` AS `t1_c1`, `author`.`password` AS `t1_c2`, `author`.`email` AS `t1_c3` FROM `tbl_user` `author` WHERE (`author`.`id`=:ypl0). Bound with :ypl0='8'
	}

	public function actionGetPostWithUserById2($id)
	{
		$post = Post::model()->with("author")->findByPk($id);
		echo "Title : " . $post->title . "<br />";
		echo "Author : " . $post->author->username . "<br />";
		echo "Content : " . $post->content;

		// SELECT `t`.`id` AS `t0_c0`, `t`.`title` AS `t0_c1`, `t`.`content` AS `t0_c2`, `t`.`create_time` AS `t0_c3`, `t`.`author_id` AS `t0_c4`, `t`.`published` AS `t0_c5`, `author`.`id` AS `t1_c0`, `author`.`username` AS `t1_c1`, `author`.`password` AS `t1_c2`, `author`.`email` AS `t1_c3` FROM `tbl_post` `t` LEFT OUTER JOIN `tbl_user` `author` ON (`t`.`author_id`=`author`.`id`) WHERE (`t`.`id`=10)
	}

	public function actionGetPostsWithUserAndCategories()
	{
		$posts = Post::model()->findAll(array('limit' => 50));

		foreach ($posts as $post)
		{
				echo "Title : " . $post->title . "<br />";
				echo "Author : " . $post->author->username . "<br />";
				echo "Post categories : ";

				$categories = array();
				foreach ($post->categories as $category) {
					$categories[] = $category->name;
				}

				echo implode(", ", $categories);
				echo "<br /><br />";
		}

		// SELECT * FROM `tbl_post` `t` LIMIT 50
		// loop 50x this query
		// SELECT `author`.`id` AS `t1_c0`, `author`.`username` AS `t1_c1`, `author`.`password` AS `t1_c2`, `author`.`email` AS `t1_c3` FROM `tbl_user` `author` WHERE (`author`.`id`=:ypl0). Bound with :ypl0='17'
		// ...
		// SELECT `categories`.`id` AS `t1_c0`, `categories`.`name` AS `t1_c1` FROM `tbl_category` `categories` INNER JOIN `tbl_post_category` `categories_categories` ON (`categories_categories`.`post_id`=:ypl0) AND (`categories`.`id`=`categories_categories`.`category_id`). Bound with :ypl0='17'
	}

	public function actionGetPostsWithUserAndCategories2()
	{
		$posts = Post::model()->with("author", "categories")->findAll(array('limit' => 50));

		foreach ($posts as $post)
		{
				echo "Title : " . $post->title . "<br />";
				echo "Author : " . $post->author->username . "<br />";
				echo "Post categories : ";

				$categories = array();
				foreach ($post->categories as $category) {
					$categories[] = $category->name;
				}

				echo implode(", ", $categories);
				echo "<br /><br />";
		}

		// akan menjalankan 2 query
		// SELECT `t`.`id` AS `t0_c0`, `t`.`title` AS `t0_c1`, `t`.`content` AS `t0_c2`, `t`.`create_time` AS `t0_c3`, `t`.`author_id` AS `t0_c4`, `t`.`published` AS `t0_c5`, `author`.`id` AS `t1_c0`, `author`.`username` AS `t1_c1`, `author`.`password` AS `t1_c2`, `author`.`email` AS `t1_c3` FROM `tbl_post` `t` LEFT OUTER JOIN `tbl_user` `author` ON (`t`.`author_id`=`author`.`id`) LIMIT 50
		// SELECT `t`.`id` AS `t0_c0`, `categories`.`id` AS `t2_c0`, `categories`.`name` AS `t2_c1` FROM `tbl_post` `t` LEFT OUTER JOIN `tbl_post_category` `categories_categories` ON (`t`.`id`=`categories_categories`.`post_id`) LEFT OUTER JOIN `tbl_category` `categories` ON (`categories`.`id`=`categories_categories`.`category_id`) WHERE (`t`.`id` IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50))
	}

	public function actionGetPostsWithUserProfileAndCategories()
	{
		$posts = Post::model()->findAll(array('limit' => 20));

		foreach ($posts as $post)
		{
			echo "Title : " . $post->title . "<br />";
			echo "Author Bio : <br />";

			$author = $post->author;
			$profile = $author->profile;

			echo "Name : " . $author->username . "<br />";
			echo "Photo : " . $profile->photo . "<br />";
			echo "Website : " . $profile->website . "<br />";
			echo "Post categories : ";

			$categories = array();
			foreach ($post->categories as $category) {
				$categories[] = $category->name;
			}

			echo implode(", ", $categories);
			echo "<br /><br />";

			echo "Other posts from user : <br />";
			echo "<ol>";
			$userPosts = $author->posts(array('condition' => 'id != :id', 'params' => array(':id' => $post->id)));
			foreach ($userPosts as $userPost)
			{
				echo "<li>" . $userPost->title . "</li>";
			}
			echo "</ol>";
			echo "<hr />";
		}

		// SELECT * FROM `tbl_post` `t` LIMIT 20
		// loop 20x this query:
		// SELECT `author`.`id` AS `t1_c0`, `author`.`username` AS `t1_c1`, `author`.`password` AS `t1_c2`, `author`.`email` AS `t1_c3` FROM `tbl_user` `author` WHERE (`author`.`id`=:ypl0). Bound with :ypl0='4'
		// SELECT `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_post` `posts` WHERE (id != :id) AND (`posts`.`author_id`=:ypl0). Bound with :id='2', :ypl0='4'
		// SELECT `profile`.`owned_id` AS `t1_c0`, `profile`.`photo` AS `t1_c1`, `profile`.`website` AS `t1_c2` FROM `tbl_profile` `profile` WHERE (`profile`.`owned_id`=:ypl0). Bound with :ypl0='4'
		// SELECT `categories`.`id` AS `t1_c0`, `categories`.`name` AS `t1_c1` FROM `tbl_category` `categories` INNER JOIN `tbl_post_category` `categories_categories` ON (`categories_categories`.`post_id`=:ypl0) AND (`categories`.`id`=`categories_categories`.`category_id`). Bound with :ypl0='2'
	}

	public function actionGetPostsWithUserProfileAndCategories2()
	{
		$posts = Post::model()->with(
			'author.profile',
			'author.posts',
			'categories'
		)->findAll(array('limit' => 20));

		foreach ($posts as $post)
		{
			echo "Title : " . $post->title . "<br />";
			echo "Author Bio : <br />";

			$author = $post->author;
			$profile = $author->profile;

			echo "Name : " . $author->username . "<br />";
			echo "Photo : " . $profile->photo . "<br />";
			echo "Website : " . $profile->website . "<br />";
			echo "Post categories : ";

			$categories = array();
			foreach ($post->categories as $category) {
				$categories[] = $category->name;
			}

			echo implode(", ", $categories);
			echo "<br /><br />";

			echo "Other posts from user : <br />";
			echo "<ol>";
			$userPosts = $author->posts(array('condition' => 'id != :id', 'params' => array(':id' => $post->id)));
			foreach ($userPosts as $userPost)
			{
				echo "<li>" . $userPost->title . "</li>";
			}
			echo "</ol>";
			echo "<hr />";

			// SELECT `t`.`id` AS `t0_c0`, `t`.`title` AS `t0_c1`, `t`.`content` AS `t0_c2`, `t`.`create_time` AS `t0_c3`, `t`.`author_id` AS `t0_c4`, `t`.`published` AS `t0_c5`, `author`.`id` AS `t1_c0`, `author`.`username` AS `t1_c1`, `author`.`password` AS `t1_c2`, `author`.`email` AS `t1_c3`, `profile`.`owned_id` AS `t2_c0`, `profile`.`photo` AS `t2_c1`, `profile`.`website` AS `t2_c2` FROM `tbl_post` `t` LEFT OUTER JOIN `tbl_user` `author` ON (`t`.`author_id`=`author`.`id`) LEFT OUTER JOIN `tbl_profile` `profile` ON (`profile`.`owned_id`=`author`.`id`) LIMIT 20
			// SELECT `author`.`id` AS `t1_c0`, `posts`.`id` AS `t3_c0`, `posts`.`title` AS `t3_c1`, `posts`.`content` AS `t3_c2`, `posts`.`create_time` AS `t3_c3`, `posts`.`author_id` AS `t3_c4`, `posts`.`published` AS `t3_c5` FROM `tbl_user` `author` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`author`.`id`) WHERE (`author`.`id` IN (4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14))
			// SELECT `t`.`id` AS `t0_c0`, `categories`.`id` AS `t4_c0`, `categories`.`name` AS `t4_c1` FROM `tbl_post` `t` LEFT OUTER JOIN `tbl_post_category` `categories_categories` ON (`t`.`id`=`categories_categories`.`post_id`) LEFT OUTER JOIN `tbl_category` `categories` ON (`categories`.`id`=`categories_categories`.`category_id`) WHERE (`t`.`id` IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20))
			// loop 20x this query
			// SELECT `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_post` `posts` WHERE (id != :id) AND (`posts`.`author_id`=:ypl0). Bound with :id='3', :ypl0='4'
		}
	}

	public function actionGetUsersWithProfileAndLast3Posts()
	{
		$users = User::model()->findAll(array('limit' => 10));

		foreach ($users as $user)
		{
			echo "Author : " . $user->username . "<br />";
			echo "Website : " . $user->profile->website . "<br />";
			echo "Last post : <br />";

			$posts = $user->posts(array('order' => 'posts.create_time desc', 'limit' => 3));
			echo "<ul>";
			foreach ($posts as $post) {
				echo "<li>" . $post->title . "</li>";
			}
			echo "</ul>";
		}

		// SELECT * FROM `tbl_user` `t` LIMIT 10
		// loop 10x
		// SELECT `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_post` `posts` WHERE (`posts`.`author_id`=:ypl0) ORDER BY posts.create_time desc LIMIT 3. Bound with :ypl0='8'
		// SELECT `profile`.`owned_id` AS `t1_c0`, `profile`.`photo` AS `t1_c1`, `profile`.`website` AS `t1_c2` FROM `tbl_profile` `profile` WHERE (`profile`.`owned_id`=:ypl0). Bound with :ypl0='10'
	}

	public function actionGetUsersWithProfileAndLast3Posts2()
	{
		$criteria = new CDbCriteria();
		// $criteria->limit = 10;
		$criteria->with = array(
			'posts' => array('order' => 'posts.create_time desc'),
			'profile',
		);
		$criteria->limit = 10;

		$users = User::model()->findAll($criteria);

		/*
		$users = User::model()->with(array(
			'posts' => array('order' => 'posts.create_time desc'),
			'profile',
		))->findAll();*/

		foreach ($users as $user)
		{
			echo "Author : " . $user->username . "<br />";
			echo "Website : " . $user->profile->website . "<br />";
			echo "Last post : <br />";

			echo "<ul>";
			foreach ($user->posts as $post) {
				echo "<li>" . $post->title . "</li>";
			}
			echo "</ul>";
		}

		// tampa limit akan menjalankan 1 query
		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3`, `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5`, `profile`.`owned_id` AS `t2_c0`, `profile`.`photo` AS `t2_c1`, `profile`.`website` AS `t2_c2` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) LEFT OUTER JOIN `tbl_profile` `profile` ON (`profile`.`owned_id`=`t`.`id`) ORDER BY posts.create_time desc

		// dengan limit
		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3`, `profile`.`owned_id` AS `t2_c0`, `profile`.`photo` AS `t2_c1`, `profile`.`website` AS `t2_c2` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_profile` `profile` ON (`profile`.`owned_id`=`t`.`id`) LIMIT 10
		// SELECT `t`.`id` AS `t0_c0`, `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (`t`.`id` IN (4, 5, 6, 7, 8, 9, 10, 11, 12, 13)) ORDER BY posts.create_time desc
	}

	public function actionGetLast10PostsWithComments()
	{
		$posts = Post::model()->findAll(array('limit' => 10, 'order' => 'create_time desc'));
		foreach ($posts as $no => $post)
		{
			echo "Post No $no " . $post->title . "<br />";
			foreach ($post->comments as $comment) {
				echo $comment->author . " says " . $comment->content . "<br /><br />";
			}
			echo "<hr />";
		}

		// SELECT * FROM `tbl_post` `t` ORDER BY create_time desc LIMIT 10
		// loop 10x
		// SELECT `comments`.`id` AS `t1_c0`, `comments`.`post_id` AS `t1_c1`, `comments`.`content` AS `t1_c2`, `comments`.`author` AS `t1_c3`, `comments`.`visible` AS `t1_c4` FROM `tbl_comment` `comments` WHERE (`comments`.`post_id`=:ypl0). Bound with :ypl0='338'
	}

	public function actionGetLast10PostsWithComments2()
	{
		$posts = Post::model()->with(array(
			'comments' => array('together' => false)
		))->findAll(array(
			'limit' => 10,
			'order' => 'create_time desc')
		);

		foreach ($posts as $no => $post)
		{
			echo "Post No $no " . $post->title . "<br />";
			foreach ($post->comments as $comment) {
				echo $comment->author . " says " . $comment->content . "<br /><br />";
			}
			echo "<hr />";
		}

		// SELECT `t`.`id` AS `t0_c0`, `t`.`title` AS `t0_c1`, `t`.`content` AS `t0_c2`, `t`.`create_time` AS `t0_c3`, `t`.`author_id` AS `t0_c4`, `t`.`published` AS `t0_c5` FROM `tbl_post` `t` ORDER BY create_time desc LIMIT 10
		// SELECT `t`.`id` AS `t0_c0`, `comments`.`id` AS `t1_c0`, `comments`.`post_id` AS `t1_c1`, `comments`.`content` AS `t1_c2`, `comments`.`author` AS `t1_c3`, `comments`.`visible` AS `t1_c4` FROM `tbl_post` `t` LEFT OUTER JOIN `tbl_comment` `comments` ON (`comments`.`post_id`=`t`.`id`) WHERE (`t`.`id` IN (330, 331, 332, 333, 334, 335, 336, 337, 338, 339))

	}

	public function actionGetPostsByTitle($title)
	{
		// Show all posts that has a word in post title
		$criteria = new CDbCriteria();
		$criteria->compare('title', $title, true);
		$posts = Post::model()->findAll($criteria);

		echo "<ul>";
		foreach ($posts as $post) {
			echo "<li>" . $post->title . "</li>";
		}
		echo "</ul>";
	}

	public function actionGetPostsWithAuthorByTitle($title)
	{
		/// Show all posts with their authors that has a certain word in post title
		$criteria = new CDbCriteria();
		$criteria->with = array('author');
		$criteria->compare("t.title", $title, true);
		$posts = Post::model()->findAll($criteria);

		echo "<ul>";
		foreach ($posts as $post) {
			echo "<li>" . $post->title . " created by <strong>" . $post->author->username . "</strong></li>";
		}
		echo "</ul>";

		/*
		SELECT `t`.`id` AS `t0_c0`, `t`.`title` AS `t0_c1`, `t`.`content` AS `t0_c2`,
		`t`.`create_time` AS `t0_c3`, `t`.`author_id` AS `t0_c4`, `t`.`published` AS `t0_c5`,
		`author`.`id` AS `t1_c0`, `author`.`username` AS `t1_c1`, `author`.`password` AS `t1_c2`,
		`author`.`email` AS `t1_c3`
		FROM `tbl_post` `t` LEFT OUTER JOIN `tbl_user` `author` ON (`t`.`author_id`=`author`.`id`)
		WHERE (t.title LIKE :ycp0). Bound with :ycp0='%post no 1%'
		*/
	}

	public function actionGetAuthorsByPostTitle($title)
	{
		// Show all authors who has at least one post that has a certain word in post title
		$criteria = new CDbCriteria();
		$criteria->with = array('posts');
		$criteria->compare("posts.title", $title, true);
		$users = User::model()->findAll($criteria);

		echo "<ul>";
		foreach ($users as $user) {
			echo "<li>" . $user->username . "</li>";
		}
		echo "</ul>";

		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3`, `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (posts.title LIKE :ycp0). Bound with :ycp0='%post no 1%'
	}

	public function actionGetAuthorsWithPostsByPostTitle($title)
	{
		// Show all authors with his/her all posts who has at least one post that has a certain word in post title
		$criteria = new CDbCriteria();
		$criteria->with = array('posts' => array('select' => false));
		$criteria->compare("posts.title", $title, true);
		$users = User::model()->findAll($criteria);

		echo "<ul>";
		foreach ($users as $user) {
			echo "<li>" . $user->username;
			echo "<ul>";

			foreach ($user->posts as $post) {
				echo "<li>" . $post->title . "</li>";
			}

			echo "</ul>";
			echo  "</li>";
		}
		echo "</ul>";

		// select all field in posts ( $criteria->with = array('posts'); )
		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3`, `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (posts.title LIKE :ycp0). Bound with :ycp0='%name1%'

		// just select field title in posts table ( $criteria->with = array('posts' => array('select' => 'title')); )
		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3`, `posts`.`title` AS `t1_c1`, `posts`.`id` AS `t1_c0` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (posts.title LIKE :ycp0). Bound with :ycp0='%name1%'

		// not select field  in posts table ( $criteria->with = array('posts' => array('select' => false)); )
		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (posts.title LIKE :ycp0). Bound with :ycp0='%name1%'
		//
		// karena di kode program mencoba untuk mengakses $post->title, akan me-looping query sbb:
		// SELECT `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_post` `posts` WHERE (`posts`.`author_id`=:ypl0). Bound with :ypl0='4'
	}


	public function actionGetTop5AuthorsWithPostsByPostTitle($title)
	{
		$criteria = new CDbCriteria();
		$criteria->with = array('posts' => array('together' => true, 'select' => false));
		$criteria->compare("posts.title", $title, true);
		$criteria->limit = 5;
		$criteria->order = "t.username ASC";
		$criteria->group = "t.id";
		$users = User::model()->findAll($criteria);

		echo "<ul>";
		foreach ($users as $user) {
			echo "<li>" . $user->username;
			echo "<ul>";

			foreach ($user->posts as $post) {
				echo "<li>" . $post->title . "</li>";
			}

			echo "</ul>";
			echo  "</li>";
		}
		echo "</ul>";

		// percobaan 1
		// $criteria->with = array('posts');
		// $criteria->limit = 5;
		// akan menampilkan error:
		// Column not found: 1054 Unknown column 'posts.title' in 'where clause'
		// Tips: By default, Yii uses eager loading, i.e., generating a single SQL statement, except when LIMIT is applied to the primary model.
		//
		// $criteria->with = array('posts' => array('together' => true, 'select' => false));
		// $criteria->limit = 5;
		// tidak akan muncul error tapi data yang ditampilkan masih tidak benar:
		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3`, `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (posts.title LIKE :ycp0) ORDER BY t.username ASC LIMIT 5. Bound with :ycp0='%name1%'
		//
		// $criteria->with = array('posts' => array('together' => true, 'select' => false));
		// $criteria->limit = 5;
		// $criteria->group = "t.id";
		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (posts.title LIKE :ycp0) GROUP BY t.id ORDER BY t.username ASC LIMIT 5. Bound with :ycp0='%name1%')
		// n kali:
		// SELECT `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_post` `posts` WHERE (`posts`.`author_id`=:ypl0). Bound with :ypl0='113'
	}

	public function actionGetTop5AuthorsWithPostsByPostTitle2($title)
	{
		$criteria = new CDbCriteria();
		$criteria->with = array('posts' => array('together' => true, 'select' => false));
		$criteria->compare("posts.title", $title, true);
		$criteria->order = "t.username ASC";
		$criteria->group = "t.id";
		$criteria->limit = 5;
		$users = User::model()->findAll($criteria);

		echo "<ul>";
		foreach ($users as $user) {
			echo "<li>" . $user->username;
			echo "<ul>";

			$filteredPosts = $user->posts(array(
				'condition' => 'title LIKE :title',
				'params' => array(':title' => '%' . $title . '%')
			));

			foreach ($filteredPosts as $post) {
				echo "<li>" . $post->title . "</li>";
			}

			echo "</ul>";
			echo  "</li>";
		}
		echo "</ul>";

		// hasilnya
		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (posts.title LIKE :ycp0) GROUP BY t.id ORDER BY t.username ASC LIMIT 5. Bound with :ycp0='%name1%'
		// loop
		// SELECT `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_post` `posts` WHERE (title LIKE :title) AND (`posts`.`author_id`=:ypl0). Bound with :title='%name1%', :ypl0='13'
	}

	public function actionGetPostsWithAuthorByTitle2($title)
	{
		$criteria = new CDbCriteria();
		$criteria->with = array(
			'posts' => array(
				'together' => false
			),
			'postsSearch' => array(
				'select' => false,
				'together' => true
			)
		);
		$criteria->compare('postsSearch.title', $title, true);
		$users = User::model()->findAll($criteria);

		echo "<ul>";
		foreach ($users as $user) {
			echo "<li>" . $user->username;
			echo "<ul>";

			foreach ($user->posts as $post) {
				echo "<li>" . $post->title . "</li>";
			}
			echo "</ul>";
		}
		echo "</ul>";

		// ini akan menjalankan hanya 2 query
		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `postsSearch` ON (`postsSearch`.`author_id`=`t`.`id`) WHERE (postsSearch.title LIKE :ycp0). Bound with :ycp0='%post no 1%'
		// SELECT `t`.`id` AS `t0_c0`, `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (`t`.`id` IN (4, 5, 6, 8, 9, 10, 12, 15, 16, 17, 18, 19, 20, 21, 24, 25, 26, 27, 28, 30, 31, 32, 36, 38, 39, 40, 44, 48, 50, 51, 52, 53, 54, 55, 57, 59, 61, 62, 63, 64, 66, 67, 72, 76, 80, 82, 83, 85, 87, 89, 91, 92, 93, 95, 96, 104, 108, 109, 110, 113, 114, 116, 117, 118, 120, 122, 125, 129, 130, 133, 135, 136, 138, 139, 142, 144, 145, 146, 147, 148, 150, 151, 153, 154, 155, 157, 158, 161, 162, 163, 165, 166, 170, 171, 174, 175, 178, 180, 181, 182, 183, 184, 185, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 199, 201))
	}


	public function actionGetTop5AuthorsWithPostsByPostTitle3($title)
	{
		$criteria = new CDbCriteria();
		$criteria->with = array(
			'posts' => array(
				'together' => false
			),
			'postsSearch' => array(
				'select' => false,
				'together' => true
			)
		);
		$criteria->compare('postsSearch.title', $title, true);
		$criteria->group = "t.id";
		$criteria->limit = 5;
		$criteria->order = "t.username asc";

		$users = User::model()->findAll($criteria);

		echo "<ul>";
		foreach ($users as $user) {
			echo "<li>" . $user->username;
			echo "<ul>";

			foreach ($user->posts as $post) {
				echo "<li>" . $post->title . "</li>";
			}
			echo "</ul>";
		}
		echo "</ul>";

		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `postsSearch` ON (`postsSearch`.`author_id`=`t`.`id`) WHERE (postsSearch.title LIKE '%name1%') GROUP BY t.id ORDER BY t.username asc LIMIT 5
		// SELECT `t`.`id` AS `t0_c0`, `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (`t`.`id` IN (103, 112, 113, 114, 115))
	}


	public function actionGetTop5AuthorsWithPostsByPostTitle4($title)
	{
		$criteria = new CDbCriteria();
		$criteria->with = array(
			'posts' => array(
				'together' => false,
				'condition' => 'posts.title LIKE :title',
				'params' => array(':title' => '%' . $title . '%')
			),
			'postsSearch' => array(
				'select' => false,
				'together' => true
			)
		);
		$criteria->compare('postsSearch.title', $title, true);
		$criteria->group = "t.id";
		$criteria->limit = 5;
		$criteria->order = "t.username asc";

		$users = User::model()->findAll($criteria);

		echo "<ul>";
		foreach ($users as $user) {
			echo "<li>" . $user->username;
			echo "<ul>";

			foreach ($user->posts as $post) {
				echo "<li>" . $post->title . "</li>";
			}
			echo "</ul>";
		}
		echo "</ul>";

		// SELECT `t`.`id` AS `t0_c0`, `t`.`username` AS `t0_c1`, `t`.`password` AS `t0_c2`, `t`.`email` AS `t0_c3` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `postsSearch` ON (`postsSearch`.`author_id`=`t`.`id`) WHERE (postsSearch.title LIKE '%name1%') GROUP BY t.id ORDER BY t.username asc LIMIT 5
		// SELECT `t`.`id` AS `t0_c0`, `posts`.`id` AS `t1_c0`, `posts`.`title` AS `t1_c1`, `posts`.`content` AS `t1_c2`, `posts`.`create_time` AS `t1_c3`, `posts`.`author_id` AS `t1_c4`, `posts`.`published` AS `t1_c5` FROM `tbl_user` `t` LEFT OUTER JOIN `tbl_post` `posts` ON (`posts`.`author_id`=`t`.`id`) WHERE (`t`.`id` IN (103, 112, 113, 114, 115))
	}


	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
