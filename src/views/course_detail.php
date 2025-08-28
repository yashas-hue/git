<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="mb-1"><?= htmlspecialchars($course['title']) ?></h2>
      <div class="text-muted">Price: ₹<?= htmlspecialchars($course['price']) ?></div>
    </div>
    <div>
      <div class="input-group">
        <span class="input-group-text">Referral</span>
        <input id="refCode" class="form-control" placeholder="Enter referral code">
        <button class="btn btn-primary" id="buyBtn" disabled data-course-id="<?= (int)$course['id'] ?>">Pay via UPI</button>
      </div>
      <div class="form-text">Enter a valid referral code to enable purchase.</div>
    </div>
  </div>

  <?php foreach (($topics ?? []) as $t): ?>
    <div class="kp-topic card kp-card mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><?= htmlspecialchars($t['title']) ?> <?= ($t['is_free'] ? '<span class="badge bg-success">Free</span>' : '') ?></h5>
        </div>
        <?php foreach (($subtopicsByTopic[$t['id']] ?? []) as $st): ?>
          <div class="mt-3">
            <h6 class="mb-2">Subtopic: <?= htmlspecialchars($st['title']) ?></h6>
            <div class="table-responsive">
              <table class="table table-sm align-middle">
                <thead><tr><th>Video</th><th>Materials</th><th>Worksheets</th></tr></thead>
                <tbody>
                  <tr>
                    <td>
                      <?php if ($st['video_id']): ?>
                        <button class="btn btn-outline-primary btn-sm" onclick="playVideo(<?= (int)$st['video_id'] ?>)">Play</button>
                      <?php else: ?>
                        <span class="text-muted">No video</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="text-muted small">Download links via signed URLs</span>
                    </td>
                    <td><span class="text-muted small">Worksheets links</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="mt-2 small">Related support / ideas: coming soon</div>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="modal fade" id="upiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">UPI Payment</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div id="upiArea">
            <p>Scan the QR with your UPI app or click Pay Now on mobile.</p>
            <div class="text-center">
              <canvas id="qrCanvas" width="240" height="240"></canvas>
            </div>
            <div class="d-grid mt-3">
              <a class="btn btn-primary" id="upiLink" href="#">Pay Now</a>
            </div>
            <div class="mt-3">
              <label class="form-label">Transaction ID (optional)</label>
              <input id="txnId" class="form-control" placeholder="Enter UPI transaction ID">
              <button class="btn btn-success mt-2" id="iPaidBtn">I have paid</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
