// Theme toggle
document.addEventListener('click', (e)=>{
  const t = e.target;
  if (t && t.id === 'theme-toggle') {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme') || 'dark';
    const next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    document.cookie = 'theme=' + next + ';path=/;max-age=31536000';
  }
});

// Forgot password modal submit
document.addEventListener('click', async (e)=>{
  if (e.target && e.target.id === 'forgotSubmit'){
    const form = document.getElementById('forgotForm');
    const data = new FormData(form);
    const res = await fetch('/forgot-password', {method:'POST', body:data});
    const j = await res.json();
    if (j.ok){
      alert('Request submitted. Our team will contact you.');
      document.querySelector('#forgotModal .btn-close').click();
    } else { alert(j.error || 'Error'); }
  }
});

// Account save
document.addEventListener('click', async (e)=>{
  if (e.target && e.target.id === 'saveAccount'){
    const form = document.getElementById('accountForm');
    const data = new FormData(form);
    data.append('_method','PUT');
    const res = await fetch('/account', {method:'POST', body:data});
    const j = await res.json();
    if (j.ok){ alert('Saved'); } else { alert(j.error || 'Error'); }
  }
});

// Referral validation and purchase
document.addEventListener('input', async (e)=>{
  if (e.target && e.target.id === 'refCode'){
    const code = e.target.value.trim();
    const buyBtn = document.getElementById('buyBtn');
    if (!buyBtn) return;
    if (!code){ buyBtn.disabled = true; return; }
    const fd = new FormData();
    const csrf = document.querySelector('input[name="csrf_token"]');
    if (csrf) fd.append('csrf_token', csrf.value);
    fd.append('code', code);
    const res = await fetch('/validate-referral', {method:'POST', body: fd});
    const j = await res.json();
    buyBtn.disabled = !j.ok;
    buyBtn.title = j.ok ? '' : 'Enter a valid referral code to purchase';
  }
});

// Buy click
document.addEventListener('click', async (e)=>{
  if (e.target && e.target.id === 'buyBtn'){
    const courseId = e.target.getAttribute('data-course-id');
    const ref = document.getElementById('refCode').value.trim();
    const fd = new FormData();
    const csrf = document.querySelector('input[name="csrf_token"]');
    if (csrf) fd.append('csrf_token', csrf.value);
    fd.append('course_id', courseId);
    fd.append('referral_code', ref);
    const res = await fetch('/purchase-course', {method:'POST', body:fd});
    const j = await res.json();
    if (!j.ok){ alert(j.error || 'Error'); return; }
    // Show modal and render QR
    const modalEl = document.getElementById('upiModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
    document.getElementById('upiLink').href = j.upi_link;
    drawQr('qrCanvas', j.qr_text);
    window.__currentPurchaseId = j.purchase_id;
  }
});

// I Paid
document.addEventListener('click', async (e)=>{
  if (e.target && e.target.id === 'iPaidBtn'){
    const txn = document.getElementById('txnId').value.trim();
    const fd = new FormData();
    const csrf = document.querySelector('input[name="csrf_token"]');
    if (csrf) fd.append('csrf_token', csrf.value);
    fd.append('purchase_id', window.__currentPurchaseId || '');
    fd.append('payment_txn_id', txn);
    const res = await fetch('/verify-payment', {method:'POST', body:fd});
    const j = await res.json();
    if (j.ok){ alert('Payment submitted for verification'); location.reload(); } else { alert(j.error || 'Error'); }
  }
});

// Admin mark paid
async function adminVerify(id){
  const fd = new FormData();
  const csrf = document.querySelector('input[name="csrf_token"]');
  if (csrf) fd.append('csrf_token', csrf.value);
  fd.append('purchase_id', id);
  fd.append('txn_id', document.getElementById('txn-' + id).value);
  const res = await fetch('/admin/verify-payment', {method:'POST', body:fd});
  const j = await res.json();
  if (j.ok){ alert('Marked paid'); location.reload(); } else { alert(j.error || 'Error'); }
}
window.adminVerify = adminVerify;

// Minimal QR generator (naive): use third-party or server-side in production
function drawQr(canvasId, text){
  const c = document.getElementById(canvasId);
  if (!c) return;
  const ctx = c.getContext('2d');
  ctx.fillStyle = '#fff'; ctx.fillRect(0,0,c.width,c.height);
  ctx.fillStyle = '#000';
  // Placeholder pattern to indicate QR area
  for (let y=0; y<c.height; y+=10){ for (let x=0; x<c.width; x+=10){ if ((x*y + text.length) % 17 < 8) ctx.fillRect(x,y,8,8); }}
}

// Video play stub
async function playVideo(mediaId){
  const res = await fetch('/media/token?media_id=' + encodeURIComponent(mediaId));
  const j = await res.json();
  if (j.ok){ alert('Tokenized URL: ' + j.url + '\nImplement HLS player.'); }
}
window.playVideo = playVideo;

