<?php echo $this->render('resources/template_header.inc.html',NULL,get_defined_vars(),0); ?>

<section class="frame">
  <a href="<?= 'myblogs/' . $author ?>">
    <img src="<?= 'images/profiles/' . $authorImage ?>" class="img-thumbnail img-lrg pull-right" alt="<?= 'See More By ' . $authorName ?>" />
  </a>
  <h1 class="text-center"><?= $blogTitle ?></h1>
  <h4><a href="<?= 'profile' . $author ?>"><?= $authorName ?></a>
  <span class="pull-right inline"><?= $dateAdded ?></span></h4>
  <div class="blogrule">
    Word Count: <?= $wordCount.PHP_EOL ?>
    <span class="pull-right inline">Last Edited: <?= $dateEdited ?></span>
  </div>
  <p class="blog-content"><?= $this->raw($blogContent) ?></p>

</section>

<?php echo $this->render('resources/template_footer.inc.html',NULL,get_defined_vars(),0); ?>