<?php
/* @var $this PostController */
/* @var $data Post */
?>

<div class="view">

    <h3><?php echo CHtml::link(CHtml::encode($data->title), 
        array('view', 'id'=>$data->id)); ?></h3>
    
    <?php echo CHtml::encode($data->content); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('tags')); ?>:</b>
    <?php echo CHtml::encode($data->tags); ?>
    <br />

    <b><?php echo 'Created'; ?>:</b>
    <?php echo date('j F Y',$data->create_time); ?>
    <br />

    <b><?php echo 'Updated'; ?>:</b>
    <?php echo date('j F Y',$data->update_time); ?>
    <br />

</div>