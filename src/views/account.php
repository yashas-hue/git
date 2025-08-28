<div class="container py-4">
  <h2>Account</h2>
  <div class="card kp-card">
    <div class="card-body">
      <form id="accountForm">
        <?= App\security\Csrf::field() ?>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Name</label>
            <input name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label">Username</label>
            <input class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>" disabled>
          </div>
          <div class="col-12">
            <label class="form-label">Referral Code</label>
            <div class="input-group">
              <input class="form-control" value="<?= htmlspecialchars($user['referral_code'] ?? '') ?>" id="refCopy" readonly>
              <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('refCopy').value)">Copy</button>
            </div>
          </div>
        </div>
        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-primary" id="saveAccount" type="button">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
