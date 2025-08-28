<div class="container py-4">
  <h2 class="mb-3">Courses</h2>
  <div class="row g-3">
    <?php foreach (($courses ?? []) as $course): ?>
      <div class="col-sm-6 col-lg-4">
        <div class="card kp-card h-100">
          <img src="<?= htmlspecialchars($course['thumbnail_url'] ?? '/assets/images/course_placeholder.jpg') ?>" class="card-img-top" alt="Course thumbnail">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
            <p class="card-text small text-muted">Category: <?= htmlspecialchars($course['category_name'] ?? '-') ?></p>
            <p class="card-text">₹<?= htmlspecialchars($course['price']) ?></p>
            <a href="/course/<?= htmlspecialchars($course['slug']) ?>" class="btn btn-primary">View</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
