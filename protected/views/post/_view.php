<?php
/* @var $this PostController */
/* @var $data Post */
?>

<div class="view">

	<!-- <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br /> 
	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b> -->
	<h3><?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id'=>$data->id)); ?></h3>
	<!--<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b> -->
	
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tags')); ?>:</b>
	<?php echo CHtml::encode($data->tags); ?>
	<br />

	<!--<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br /-->

	<b><?php echo 'Created'; ?>:</b>
	<?php echo date('j F Y',$data->create_time); ?>
	<br />

	<b><?php echo 'Updated'; ?>:</b>
	<?php echo date('j F Y',$data->update_time); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('author_id')); ?>:</b>
	<?php echo CHtml::encode($data->author_id); ?>
	<br />

	*/ ?>

</div>