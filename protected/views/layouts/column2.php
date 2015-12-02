<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-18">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-6 last">
	<div id="sidebar">
 	    <?php if(!Yii::app()->user->isGuest) $this->widget('UserMenu'); ?>
	    <?php $this->widget('TagCloud'); ?>
	</div><!-- sidebar -->
</div>

<?php $this->endContent(); ?>