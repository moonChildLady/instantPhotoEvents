<?php

class EventsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	
	
	public function actions()
    {
        return array(
            'coco'=>array(
                'class'=>'CocoAction',
            ),
			'resized' => array(
            'class'   => 'ext.resizer.ResizerAction',
            'options' => array(
                // Tmp dir to store cached resized images 
                'cache_dir'   => Yii::getPathOfAlias('webroot') . '/assets/cache/',
 
                // Web root dir to search images from
                'base_dir'    => Yii::getPathOfAlias('webroot') . '/',
            )
        ),
    );

    }
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','show','qrcode','resized'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','upload','deleteCollection','coco'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Events;
		$model->scenario="create";
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Events']))
		{
			$model->attributes=$_POST['Events'];
			if($model->passCode==1){
				$digits = 4;
				$model->passCode = rand(pow(10, $digits-1), pow(10, $digits)-1);
			}else{
				$model->passCode = "";
			}
				$rnd = rand(0,9999);
			 $uploadedFile = CUploadedFile::getInstance($model,'bannerImg');
			 $fileName = "{$rnd}-{$uploadedFile}";
			 $model->bannerImg = $fileName;
			if($model->save()){
				mkdir(Yii::app()->params['homeDir']."/".$model->id."/".$model->collection, 0777, true);
				//$uploadedFile->saveAs(Yii::app()->params['homeDir']."/".$model->id."/".$fileName);
				$uploadedFile->saveAs(Yii::app()->params['bannerDir']."/".$fileName);
				//$this->redirect(array('view','id'=>$model->id));
				$this->redirect(array('admin'));
			}
		}
		
		//if()
		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionqrcode($id){
		
		$model=$this->loadModel($id);
		$this->render('qrcode',array(
			'model'=>$model,
		));
	}
	public function actionupload($id, $collection=null){
		$model=$this->loadModel($id);
		$model->scenario="upload";
		if($collection == null){
			$lastcollection = array_values($this->getCollection($id));
			$this->redirect(array('upload','id'=>$model->id, 'collection'=>$lastcollection[0]));
		}
		
		if(isset($_POST['Events']))
		{
			
			$model->attributes=$_POST['Events'];
			if($model->save())
				$restring = preg_replace('/\s+/', '_', $model->collection);
				mkdir(Yii::app()->params['homeDir']."/".$id."/".$restring."/org", 0777, true);
				$this->redirect(array('upload','id'=>$model->id, "collection"=>$restring));
		}
		
		
		$this->render('upload',array(
			'model'=>$model,
			'collection'=>$collection
		));
	}
	public function actionshow($id){
		
		
		
		$model=$this->loadModel($id);
		$event = $this->loadModel($id);
		//&& Yii::app()->user->id!="admin"
		//echo $model->startDate.date("Y-m-d H:i:s");
		if($model->startDate > date("Y-m-d H:i:s") && Yii::app()->user->id!="admin"){
			throw new CHttpException(404,'The Events was Expired1.');
		}
		
		
		if($model->endDate < date("Y-m-d H:i:s")){
			echo $model->endDate;
		}
		if($model->endDate !="0000-00-00 00:00:00"){
			
		if($model->startDate < date("Y-m-d H:i:s") && $model->endDate < date("Y-m-d H:i:s")){
			throw new CHttpException(404,'The Events was Expired.');
		}
		
		}
		$model->scenario="passcode_input";
		//Yii::app()->session[$id] = "value";
		if(isset($_POST['Events']))
		{
			$model->attributes=$_POST['Events'];
			if($model->passCode === $event->passCode){
				Yii::app()->session["event_".$id] = $event->passCode;
				
				//exit;
				$this->redirect(array('show','id'=>$model->id));
			}
		}
		
		if(!isset(Yii::app()->session["event_".$id])){
		if(Yii::app()->user->id!="admin" && $model->passCode !=""){
			$model->passCode = "";
			$this->render('passcode',array(
			'model'=>$model,
			'event'=>$event
		));
		}else{
		$this->render('show2',array(
			'model'=>$model,
		));
		}
		}else{
			$this->render('show2',array(
			'model'=>$model,
		));
		}
		
	}
	
	public function actiondeleteCollection($id){
		if(isset($_POST)){
			$path = Yii::app()->params['homeDir']."/".$id."/".$_POST['collection'];
			$this->DeleteDir($path);
			$this->redirect(array('upload','id'=>$id));
		}
	}
	
	public function DeleteDir($path){
    if (is_dir($path) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file)
        {
            if (in_array($file->getBasename(), array('.', '..')) !== true)
            {
                if ($file->isDir() === true)
                {
                    rmdir($file->getPathName());
                }

                else if (($file->isFile() === true) || ($file->isLink() === true))
                {
                    unlink($file->getPathname());
                }
            }
        }

        return rmdir($path);
    }

    else if ((is_file($path) === true) || (is_link($path) === true))
    {
        return unlink($path);
    }

    return false;
}
	
	public function getCollection($id){
		
		
		$path = Yii::app()->params['homeDir']."/".$id;
	    $collections = array();
		$collection = array();
	    if(is_dir($path)){
			$iterator = new DirectoryIterator($path);
				foreach ($iterator as $fileinfo) {
					if ($fileinfo->isDir() && !$fileinfo->isDot()) {
						$collections[$fileinfo->getATime()] = $fileinfo->getFilename();
					}
				 }
		
		}
		krsort($collections);
		foreach($collections as $k){
			$collection[$k] = str_replace('_', ' ', $k);
		}
		return $collection;
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model->scenario="update";
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if($model->passCode != ""){
			$model->passCode = true;
		}
		$modelA=$this->loadModel($id);
		$password = $modelA->passCode;
		if(isset($_POST['Events']))
		{
			//$_POST['Events']['bannerImg'] = $model->bannerImg;
			
			
			$orgFile = $model->bannerImg;
			
			$model->attributes=$_POST['Events'];
			$date = time();
			
			
			$uploadedFile= CUploadedFile::getInstance($model,'bannerImg');
			

			
			if($model->passCode != true){
				$model->passCode = "";
			}else{
				if(strlen($password) != 4){
				$digits = 4;
				$model->passCode = rand(pow(10, $digits-1), pow(10, $digits)-1);
				
				}else{
					$model->passCode = $password;
					
				}
			}
			
			//echo var_dump($model->bannerImg);
			if (isset($uploadedFile) && count($uploadedFile) > 0) {
                $ext = pathinfo($uploadedFile, PATHINFO_EXTENSION);
                $fileName = "{$id}-{$date}.{$ext}";
                $uploadedFile->saveAs(Yii::app()->params['bannerDir']."/".$fileName);
                
                $orgFile = $fileName;
            }
			
			//echo var_dump($model->bannerImg);
			//exit;
			
			$model->save();
				
				$modelA=$this->loadModel($id);
				$modelA->scenario="update_A";
				$modelA->bannerImg = $orgFile;
				
				if($modelA->save()){
				//$this->redirect(array('view','id'=>$model->id));
				//var_dump($model->bannerImg);
				//exit;
				$this->redirect(array('admin'));
				}else{
					print_r($modelA->getErrors());
				}
			
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		$path = Yii::app()->params['homeDir']."/".$id;
		$this->DeleteDir($path);
		//$criteria = new CDbCriteria;
		//$criteria->addInCondition('eventID',$id);
		LikeRecord::model()->deleteAll("eventID = ".$id);
		ViewRecord::model()->deleteAll("eventID = ".$id);
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Events');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Events('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Events']))
			$model->attributes=$_GET['Events'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Events the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Events::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Events $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='events-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionThumbs() {
 
        $request = str_replace(DIRECTORY_SEPARATOR ."events". DIRECTORY_SEPARATOR .'thumbs', '', Yii::app()->request->requestUri);

				
        $resourcesPath = Yii::getPathOfAlias('webroot') . $request;
        $targetPath = $resourcesPath;
 
/*  echo $resourcesPath."<br>";
 echo $targetPath;
 exit; */
 
        if (preg_match('/_(\d+)x(\d+).*\.(jpg|jpeg|png|gif)/i', $resourcesPath, $matches)) {
 
            if (!isset($matches[0]) || !isset($matches[1]) || !isset($matches[2]) || !isset($matches[3]))
                throw new CHttpException(400, 'Non valid params');
 
            if (!$matches[1] || !$matches[2]) {
                throw new CHttpException(400, 'Invalid dimensions');
            }
 
            $originalFile = str_replace($matches[0], '', $resourcesPath);
			
            if (!file_exists($originalFile))
                throw new CHttpException(404, 'File not found');
 
 
            $dirname = dirname($targetPath);
			echo $targetPath;
			exit;
            if (!is_dir($dirname))
                mkdir($dirname, 0775, true);
 
 
            $image = Yii::app()->image->load($originalFile);
            $image->resize($matches[1], $matches[2]);
 
            if ($image->save($targetPath)) {
                if (Yii::app()->request->urlReferrer != Yii::app()->request->requestUri)
                    $this->refresh();
            }
 
            throw new CHttpException(500, 'Server error');
        } else {
            throw new CHttpException(400, 'Wrong params');
        }
    }
}
