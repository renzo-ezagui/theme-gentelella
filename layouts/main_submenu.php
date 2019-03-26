<?php
/* @var $this \yii\web\View */
/* @var $generators \yii\gii\Generator[] */
/* @var $activeGenerator \yii\gii\Generator */
/* @var $content string */

use yii\helpers\Html;
use app\components\widgets\MenuContent;
use app\components\widgets\MenuOption;

$controller = $this->context;
$menus = $controller->subMenu;
$route = $controller->route;
foreach ($menus as $i => $menu) {
	foreach ($menu['url'] as $key => $val) {
		$part = explode('*', $val);
		if(strpos($part[0], '$_GET') !== false)
			$menus[$i]['url'][$key] = Yii::$app->request->get($part[1]);
	}
	if(isset($menu['select'])) {
		if($menu['select'] == 'controller')
			$menus[$i]['active'] = strtolower($controller->id) == trim($menu['url'][0], '/') || preg_match('/^('.addcslashes(strtolower($controller->id), '/').')/', trim($menu['url'][0], '/'));
			// $menus[$i]['active'] = strtolower($controller->id) == str_replace('/'.$controller->action->id, '', trim($menu['url'][0], '/'));
		else if($menu['select'] == 'action')
			$menus[$i]['active'] = strtolower($controller->id.'/'.$controller->action->id) == trim($menu['url'][0], '/');
	} else
		$menus[$i]['active'] = strpos($route, trim($menu['url'][0], '/')) === 0;
} ?>

<?php $this->beginContent('@themes/gentelella/layouts/admin_default.php'); ?>

<div class="row">
	<div class="col-md-9 col-sm-8 col-xs-12">
		<?php if(Yii::$app->session->hasFlash('success'))
			echo $this->flashMessage(Yii::$app->session->getFlash('success'));
		else if(Yii::$app->session->hasFlash('error'))
			echo $this->flashMessage(Yii::$app->session->getFlash('error'), 'danger'); ?>

		<div class="x_panel">
			<div class="x_title">
				<?php echo !empty($this->params['menu']['content']) ? MenuContent::widget(['items' => $this->params['menu']['content']]) : '';?>
				<ul class="nav navbar-right panel_toolbox">
					<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					<?php if(!empty($this->params['menu']['option'])):?>
					<li class="dropdown">
						<a href="#" title="<?php echo Yii::t('app', 'Options');?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
						<?php echo MenuOption::widget(['items' => $this->params['menu']['option']]);?>
					</li>
					<?php endif;?>
					<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<?php echo $content; ?>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-4 col-xs-12">
		<div class="list-group">
			<?php foreach ($menus as $menu) {
				$label = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']) .
					Html::tag('span', Html::encode($menu['label']), []);
				$active = $menu['active'] ? ' active' : '';
				$htmlOptions = ['class' => 'list-group-item' . $active];
				if(isset($menu['htmlOptions']))
					$htmlOptions = \yii\helpers\ArrayHelper::merge($htmlOptions, $menu['htmlOptions']);
				echo Html::a($label, $menu['url'], $htmlOptions);
			} ?>
		</div>
	</div>
</div>

<?php $this->endContent(); ?>
