<?php echo $this->render('resources/template_header.inc.html',NULL,get_defined_vars(),0); ?>

<section class="frame" id="header">
  <img src="images/notepad.png" class="pull-right" id="notepad" alt="Blogging" />
  <h1><?= $header ?></h1>
</section>
<section class="frame">
  <form action="<?= $action ?>" method="POST" class="form-horizontal">
    <div class="input-group">
      <input type="text" class="form-control" name="title" id="title" value="<?= $blogTitle ?>" />
      <span class="input-group-addon">Title</span>
    </div>
    <div class="shadelabel toplabel sep-field">Blog Entry</div>
    <textarea class="form-control labeltop" name="content" id="content" rows="10"><?= $content ?></textarea>
    <button class="btn btn-success btn-lg center-block sep-field" type="submit" name="action" id="action" value="<?= $submit ?>">Save</button>
  </form>

</section>

<?php echo $this->render('resources/template_footer.inc.html',NULL,get_defined_vars(),0); ?>