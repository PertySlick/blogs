<?php echo $this->render('resources/template_header.inc.html',NULL,get_defined_vars(),0); ?>

<section class="frame" id="header">
  <img src="<?= 'images/profiles/' . $image ?>" class="img-thumbnail pull-right" id="authorImage" alt="Your Profile Image" />
  <h1>Your blogs</h1>
</section>
<section>
  <div class="col-sm-8">
    <table class="table table-hover table-border">
      <thead>
        <tr>
          <th id="blogcell">Blog</th>
          <th class="text-center" id="updatecell">Update</th>
          <th class="text-center" id="deletecell">Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($blogs?:[]) as $blog): ?>
          <tr>
            <td><a href="<?= $blog['id'] ?>"><?= $blog['title'] ?></a></td>
            <td class="text-center"><a href="<?= 'edit' . $blog['id'] ?>"><i class="fa fa-wrench" aria-hidden="true"></i></a></td>
            <td class="text-center"><a href="<?= 'delete' . $blog['id'] ?>"><i class="fa fa-times" aria-hidden="true"></i></a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="col-sm-4">
    <h4 class="profile-title text-center"><?= $author ?></h4>
    <p>Bio: <?= $bio ?></p>
  </div>
</section>

<?php echo $this->render('resources/template_footer.inc.html',NULL,get_defined_vars(),0); ?>