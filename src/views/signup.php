<div class="container py-4">
  <h2 class="mb-3">Create your account</h2>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">Please fix the errors below.</div>
  <?php endif; ?>
  <form method="post" action="/signup" class="row g-3">
    <?= App\security\Csrf::field() ?>
    <div class="col-md-6">
      <label class="form-label">Name</label>
      <input name="name" class="form-control" value="<?= htmlspecialchars($name ?? '') ?>" required>
      <?php if (!empty($errors['name'])): ?><div class="invalid-feedback d-block"><?= $errors['name'] ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
      <label class="form-label">Phone</label>
      <input name="phone" class="form-control" value="<?= htmlspecialchars($phone ?? '') ?>" required>
      <?php if (!empty($errors['phone'])): ?><div class="invalid-feedback d-block"><?= $errors['phone'] ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
      <label class="form-label">Email (optional)</label>
      <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>">
      <?php if (!empty($errors['email'])): ?><div class="invalid-feedback d-block"><?= $errors['email'] ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
      <label class="form-label">Username</label>
      <input name="username" class="form-control" value="<?= htmlspecialchars($username ?? '') ?>" required>
      <?php if (!empty($errors['username'])): ?><div class="invalid-feedback d-block"><?= $errors['username'] ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
      <label class="form-label">Password</label>
      <input name="password" type="password" class="form-control" required aria-describedby="pwHelp">
      <div id="pwHelp" class="form-text">Min 6 chars, include letters, number, symbol.</div>
      <?php if (!empty($errors['password'])): ?><div class="invalid-feedback d-block"><?= $errors['password'] ?></div><?php endif; ?>
    </div>
    <div class="col-md-6">
      <label class="form-label">Referral code (optional now; needed to purchase)</label>
      <input name="referral_code" class="form-control" value="<?= htmlspecialchars($referralInput ?? '') ?>">
      <?php if (!empty($errors['referral_code'])): ?><div class="invalid-feedback d-block"><?= $errors['referral_code'] ?></div><?php endif; ?>
    </div>
    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary" type="submit">Create account</button>
      <a href="/login" class="btn btn-link">Login</a>
    </div>
  </form>
</div>
