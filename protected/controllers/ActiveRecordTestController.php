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
