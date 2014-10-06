<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<ul>
	<li><?php echo CHtml::link("Get Post with user by ID 10 (lazy loading)", array("ActiveRecordTest/getPostWithUserById", "id" => 10)); ?></li>
	<li><?php echo CHtml::link("Get Post with user by ID 10 (eager loading)", array("ActiveRecordTest/getPostWithUserById2", "id" => 10)); ?></li>
	<li><?php echo CHtml::link("Get Posts with user and categories (lazy loading)", array("ActiveRecordTest/getPostsWithUserAndCategories")); ?></li>
	<li><?php echo CHtml::link("Get Posts with user and categories (eager loading)", array("ActiveRecordTest/getPostsWithUserAndCategories2")); ?></li>
	<li><?php echo CHtml::link("Get Posts with user profile and categories (lazy loading)", array("ActiveRecordTest/getPostsWithUserProfileAndCategories")); ?></li>
	<li><?php echo CHtml::link("Get Posts with user profile and categories (eager loading)", array("ActiveRecordTest/getPostsWithUserProfileAndCategories2")); ?></li>
	<li><?php echo CHtml::link("Get Users and profile with last 3 posts (lazy loading)", array("ActiveRecordTest/getUsersWithProfileAndLast3Posts")) ?></li>
	<li><?php echo CHtml::link("Get Users and profile with last 3 posts (eager loading)", array("ActiveRecordTest/getUsersWithProfileAndLast3Posts2")) ?></li>
	<li><?php echo CHtml::link("Get Last 10 Posts with comments (lazy loading)", array("ActiveRecordTest/getLast10PostsWithComments")) ?></li>
	<li><?php echo CHtml::link("Get Last 10 Posts with comments (eager loading)", array("ActiveRecordTest/getLast10PostsWithComments2")) ?></li>
	<li><?php echo CHtml::link("Get Posts by Title 'post No 1'", array("ActiveRecordTest/getPostsByTitle", "title" => "post no 1")) ?></li>
	<li><?php echo CHtml::link("Get Posts with Author by Title 'post No 1'", array("ActiveRecordTest/getPostsWithAuthorByTitle", "title" => "post no 1")) ?></li>
	<li><?php echo CHtml::link("Get Posts with Author by Title 'post No 1' (2)", array("ActiveRecordTest/getPostsWithAuthorByTitle2", "title" => "post no 1")) ?></li>
	<li><?php echo CHtml::link("Get Authors by post title 'post No 1'", array("ActiveRecordTest/getAuthorsByPostTitle", "title" => "post no 1")) ?></li>
	<li><?php echo CHtml::link("Get Authors with Posts by post title 'name1'", array("ActiveRecordTest/getAuthorsWithPostsByPostTitle", "title" => "name1")) ?></li>
	<li><?php echo CHtml::link("Get Top 5 Authors With Posts by post title 'name1'", array("ActiveRecordTest/getTop5AuthorsWithPostsByPostTitle", "title" => "name1")) ?></li>
	<li><?php echo CHtml::link("Get Top 5 Authors With Posts by post title 'name1' (2)", array("ActiveRecordTest/getTop5AuthorsWithPostsByPostTitle2", "title" => "name1")) ?></li>
	<li><?php echo CHtml::link("Get Top 5 Authors With Posts by post title 'name1' (3)", array("ActiveRecordTest/getTop5AuthorsWithPostsByPostTitle3", "title" => "name1")) ?></li>
	<li><?php echo CHtml::link("Get Top 5 Authors With Posts by post title 'name1' (4)", array("ActiveRecordTest/getTop5AuthorsWithPostsByPostTitle4", "title" => "name1")) ?></li>
</ul>
