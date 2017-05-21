<!--
  * Author: Timothy Roush
  * Date Created: 5/12/17
  * Assignment: The Blogs Site
  * Description:  Main Blogs Splash Page
-->

<?php echo $this->render('resources/template_header.inc.html',NULL,get_defined_vars(),0); ?>

<section>
  <?php foreach (($bloggers?:[]) as $blogger): ?>
    <div class="col-sm-4">
      <div class="card">
        <div class="img-frame">
          <img src="<?= 'images/profiles/' . $blogger->getImage() ?>"
               class="img-responsive img-thumbnail center-block" alt="Blogger Profile" />
        </div>
        <p class="text-center"><?= $blogger->getUserName() ?></p>
        <div class="cardrule">
          <a href="<?= 'blogs/' . $blogger->getID() ?>">view blogs</a>
          <span class="pull-right" style="display:inline;">Total: <?= $blogger->getBlogCount() ?></span>
        </div>
        <p><?= $blogger->getLastBlog() ?></p>
      </div>
    </div>
  <?php endforeach; ?>

</section>

<?php echo $this->render('resources/template_footer.inc.html',NULL,get_defined_vars(),0); ?>