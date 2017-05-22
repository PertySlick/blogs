<?php echo $this->render('resources/template_header.inc.html',NULL,get_defined_vars(),0); ?>

<section class="frame" id="header">
  <img src="<?= 'images/profiles/' . $image ?>" class="img-thumbnail pull-right" id="authorImage" alt="Your Profile Image" />
  <h1>Your blogs</h1>
</section>
<section class="frame">
  <div class="col-sm-8">
    TABLE
  </div>
  <div class="col-sm-4">
    <h4 class="profile-title text-center"><?= $author ?></h4>
    <p>Bio: <?= $bio ?></p>
  </div>
</section>

<?php echo $this->render('resources/template_footer.inc.html',NULL,get_defined_vars(),0); ?>