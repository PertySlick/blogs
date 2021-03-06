<?php echo $this->render('resources/template_header.inc.html',NULL,get_defined_vars(),0); ?>

<section>
  <div class="row">
    <div class="col-12">
      <h2 class="text-center"><?= $blogger->getUserName() . '\'s Blogs' ?></h2>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-9">
      <section>
        <h4 class="profile-title">My most recent blog:</h4>
        <p><?= $lastBlog ?></p>
      </section>
      <section>
        <h4>My blogs:</h4>
        <?php foreach (($blogs?:[]) as $blog): ?>
          <div class="blog-summary">
            <p>
              <a href="<?= $blog->getID() ?>"><?= $blog->getTitle() ?></a>
              - word count <?= $blog->getWordCount().PHP_EOL ?>
              - <?= date('F jS, Y', strtotime($blog->getDateAdded())).PHP_EOL ?>
            </p>
            <p><?= $blog->getContent() ?></p>
          </div>
        <?php endforeach; ?>
      </section>
    </div>
    <div class="col-sm-3">
      <img src="<?= 'images/profiles/' . $blogger->getImage() ?>"
           class="img-thumbnail center-block" alt="Profile Image" />
      <h4 class="profile-title"><?= $blogger->getUserName() ?></h4>
      <p>Bio: <?= $blogger->getBio() ?></p>
    </div>
  </div>
</section>

<?php echo $this->render('resources/template_footer.inc.html',NULL,get_defined_vars(),0); ?>