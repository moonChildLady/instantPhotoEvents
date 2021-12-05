<?php

/**
 * This is the model class for table "Events".
 *
 * The followings are the available columns in table 'Events':
 * @property string $id
 * @property string $eventName
 * @property string $passCode
 * @property string $startDate
 * @property string $endDate
 * @property string $createDate
 * @property string $active
 */
class Events extends CActiveRecord
{
	public $collection;
	public $selected_collection;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Events';
	}
	
	//public $passCode_input;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('eventName, startDate', 'required'),
			array('collection, bannerImg', 'required','on'=>'create'),
			array('collection', 'required','on'=>'upload'),
			array('eventName,startDate', 'required','on'=>'update'),
			
			array('passCode', 'required','on'=>'passcode_input'),
			array('eventName', 'length', 'max'=>200,'on'=>'create'),
			array('passCode', 'length', 'max'=>10),
			array('active', 'length', 'max'=>3),
			array('endDate,collection,selected_collection', 'safe'),
			//array('bannerImg', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true, 'on'=>'update'),
			//array('bannerImg', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true, 'on'=>'update_A'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, eventName, passCode, startDate, endDate, createDate, active,collection,selected_collection, bannerImg', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'eventName' => 'Event Name',
			'passCode' => 'PassCode',
			'passCode_input' => 'PassCode',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'createDate' => 'Create Date',
			'active' => 'Active',
			'collection'=>'Create Colllection',
			'selected_collection'=>'Select Colllection',
			'bannerImg'=>'Banner'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('eventName',$this->eventName,true);
		$criteria->compare('passCode',$this->passCode,true);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('createDate',$this->createDate,true);
		$criteria->compare('active',$this->active,true);
		$criteria->compare('bannerImg',$this->active,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Events the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function onFileUploaded($fullFileName,$userdata) {
        if(file_exists($fullFileName)){
			//unlink($fullFileName);
			
		}
		
/* 		$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$url = urlencode("fyp.dress4u.hk/events/resized/150x150/images/album/".$userdata['id']."/".$userdata['collection']."/".basename($fullFileName));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$output = curl_exec($ch);

sleep(2); */
	 $image = Yii::app()->image->load("images/album/".$userdata['id']."/".$userdata['collection']."/org/".basename($fullFileName));
     $image->resize(500, 500);
	 if ($image->save("images/album/".$userdata['id']."/".$userdata['collection']."/".basename($fullFileName))) {
       if (Yii::app()->request->urlReferrer != Yii::app()->request->requestUri){
                    $this->refresh();
		}
     }
    }
}
