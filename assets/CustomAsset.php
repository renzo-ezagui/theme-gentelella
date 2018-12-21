<?php
namespace app\themes\gentelella\assets;

class CustomAsset extends \yii\web\AssetBundle
{
	public $sourcePath = '@app/themes/gentelella';
	
	public $css = [
		'css/custom.css',
	];

	public $js = [
		'js/custom.js',
	];

	public $depends = [];

	public $publishOptions = [
		'forceCopy' => true,
	];

	public function init() {
		parent::init();
	}

}