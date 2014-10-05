<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<ul>
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
