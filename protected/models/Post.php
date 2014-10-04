<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $create_time
 * @property integer $author_id
 * @property integer $published
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property User $author
 * @property Category[] $tblCategories
 */
class Post extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, create_time, author_id', 'required'),
			array('author_id, published', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, create_time, author_id, published', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'comments' => array(self::HAS_MANY, 'Comment', 'post_id'),
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'categories' => array(self::MANY_MANY, 'Category', '{{post_category}}(post_id, category_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'content' => 'Content',
			'create_time' => 'Create Time',
			'author_id' => 'Author',
			'published' => 'Published',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('title', $this->title, true);
		$criteria->compare('content', $this->content, true);
		$criteria->compare('create_time', $this->create_time, true);
		$criteria->compare('author_id', $this->author_id);
		$criteria->compare('published', $this->published);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}
