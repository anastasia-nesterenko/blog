<ul>
    <li><?php echo CHtml::link('Create new post',array('post/create')); ?></li>
    <li><?php echo CHtml::link('Manage posts',array('post/admin')); ?></li>
    <li><?php echo CHtml::link('Commit comments',array('comment/index'))
        . ' (' . Comment::model()->pendingCommentCount . ')'; ?></li>
    <li><?php echo CHtml::link('Exit',array('site/logout')); ?></li>
</ul>