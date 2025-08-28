<div class="container py-4">
  <h2 class="mb-3">Login</h2>
  <?php if (!empty($errors['login'])): ?>
    <div class="alert alert-danger"><?= $errors['login'] ?></div>
  <?php endif; ?>
  <form method="post" action="/login" class="row g-3">
    <?= App\security\Csrf::field() ?>
    <div class="col-md-6">
      <label class="form-label">Phone / Email / Username</label>
      <input name="identity" class="form-control" value="<?= htmlspecialchars($identity ?? '') ?>" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Password</label>
      <input name="password" type="password" class="form-control" required>
    </div>
    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary" type="submit">Login</button>
      <a class="btn btn-link" href="/signup">New Member — Register</a>
      <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#forgotModal">Forgot password</button>
    </div>
  </form>

  <div class="modal fade" id="forgotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Forgot password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="forgotForm">
            <?= App\security\Csrf::field() ?>
            <label class="form-label">Phone or Email</label>
            <input name="contact" class="form-control" required>
            <label class="form-label mt-2">Message (optional)</label>
            <textarea name="message" class="form-control" rows="3"></textarea>
          </form>
          <div class="form-text">A support ticket will be created and our team will contact you.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="forgotSubmit">Submit</button>
        </div>
      </div>
    </div>
  </div>
</div>
