<?php
$quiz_details = $this->crud_model->get_lessons('lesson', $param2)->row_array();
$sections = $this->crud_model->get_section('course', $param3)->result_array();
?>
<form action="<?php echo site_url('user/quizes/'.$param3.'/edit/'.$param2); ?>" method="post">
    <div class="form-group">
        <label for="title"><?php echo translate('quiz_title'); ?></label>
        <input class="form-control" type="text" name="title" id="title" value="<?php echo $quiz_details['title']; ?>" required>
    </div>
    <div class="form-group">
        <label for="section_id"><?php echo translate('section'); ?></label>
        <select class="form-control select2" data-toggle="select2" name="section_id" id="section_id" required>
            <?php foreach ($sections as $section): ?>
                <option value="<?php echo $section['id']; ?>" <?php if ($quiz_details['section_id'] == $section['id']): ?>selected<?php endif; ?>><?php echo $section['title']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label><?php echo translate('instruction'); ?></label>
        <textarea name="summary" class="form-control"><?php echo $quiz_details['summary']; ?></textarea>
    </div>
	
	
		<div class="form-group">
			<button type="submit" name="button" class="btn btn-block btn-info btn-rounded btn-sm "><i class="fa fa-plus"></i>&nbsp;<?php echo translate('save');?></button>
		</div>
		
</form>
<script type="text/javascript">
$(document).ready(function() {
    initSelect2(['#section_id']);
});
</script>
