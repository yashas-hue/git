<div class="container py-4">
  <h2>History</h2>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead><tr><th>When</th><th>Action</th><th>Details</th></tr></thead>
      <tbody>
        <?php foreach (($history ?? []) as $h): ?>
          <tr>
            <td><?= htmlspecialchars($h['created_at']) ?></td>
            <td><?= htmlspecialchars($h['action_type']) ?></td>
            <td><code class="small"><?= htmlspecialchars($h['details']) ?></code></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
