<div class="container py-4">
  <h2>Admin Dashboard</h2>
  <h5 class="mt-4">Pending Purchases</h5>
  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead><tr><th>ID</th><th>User</th><th>Course</th><th>Amount</th><th>Created</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach (($pendingPurchases ?? []) as $p): ?>
          <tr>
            <td>#<?= (int)$p['id'] ?></td>
            <td><?= htmlspecialchars($p['user_name']) ?></td>
            <td><?= htmlspecialchars($p['course_title']) ?></td>
            <td>₹<?= htmlspecialchars($p['amount']) ?></td>
            <td><?= htmlspecialchars($p['created_at']) ?></td>
            <td>
              <div class="input-group input-group-sm">
                <span class="input-group-text">Txn</span>
                <input class="form-control" id="txn-<?= (int)$p['id'] ?>">
                <button class="btn btn-success" onclick="adminVerify(<?= (int)$p['id'] ?>)">Mark Paid</button>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
