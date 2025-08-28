<div class="container py-4">
  <div class="mb-3">
    <h2>Welcome, <?= htmlspecialchars($user['name'] ?? 'Member') ?></h2>
    <div class="small text-muted">Your referral code: <code><?= htmlspecialchars($user['referral_code'] ?? '-') ?></code></div>
  </div>

  <div class="row g-3">
    <div class="col-md-6 col-lg-4">
      <div class="card kp-card h-100">
        <div class="card-body">
          <h5 class="card-title">Courses</h5>
          <p class="card-text">Browse and continue your learning</p>
          <a href="/courses" class="btn btn-primary">Explore</a>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-4">
      <div class="card kp-card h-100">
        <div class="card-body">
          <h5 class="card-title">Consultancy</h5>
          <p class="card-text">Career, business, student, family</p>
          <a href="#" class="btn btn-outline-secondary">Book</a>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-4">
      <div class="card kp-card h-100">
        <div class="card-body">
          <h5 class="card-title">Products</h5>
          <p class="card-text">Books, furniture and more</p>
          <a href="#" class="btn btn-outline-secondary">Shop</a>
        </div>
      </div>
    </div>
  </div>
</div>
