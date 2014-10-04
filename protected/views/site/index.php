<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<ul>
	<li><?php echo CHtml::link("Get Post by Title 'post No 1'", array("site/getPostByTitle", "title" => "post no 1")) ?></li>
	<li><?php echo CHtml::link("Get Post by Title 'post No 1'", array("site/test", "id" => 1)) ?></li>
</ul>
