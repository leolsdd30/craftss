<?php
/* ─── helpers ─────────────────────────────────────────── */
function reqTimeAgo($datetime) {
    if (!$datetime) return '';
    $diff = time() - strtotime($datetime);
    if ($diff < 60)     return 'Just now';
    if ($diff < 3600)   return floor($diff / 60) . 'm ago';
    if ($diff < 86400)  return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date('M j', strtotime($datetime));
}
$csrfToken = $_SESSION['csrf_token'] ?? '';
?>

<style>
/* ── Page layout — full height, no footer, no body scroll ── */
body { overflow: hidden !important; }
body > main { overflow: hidden !important; flex: 1; display: flex; flex-direction: column; }
body footer  { display: none !important; }

#requests-page {
    display: flex;
    height: calc(100vh - 64px);
    background: #f8fafc;
    overflow: hidden;
}

/* ── Left list ──────────────────────────────────────────── */
#req-list-panel {
    width: 360px;
    min-width: 280px;
    max-width: 360px;
    flex-shrink: 0;
    background: #fff;
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    height: calc(100vh - 64px);
}

/* ── Right preview ──────────────────────────────────────── */
#req-preview-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #f8fafc;
    min-width: 0;
    overflow: hidden;
    height: calc(100vh - 64px);
}

/* ── Request cards ──────────────────────────────────────── */
.req-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 16px;
    cursor: pointer;
    transition: background 0.12s;
    border-radius: 12px;
    margin: 1px 6px;
    position: relative;
}
.req-card:hover   { background: #f1f5f9; }
.req-card.active  { background: #fffbeb; }

/* ── Preview panel — message bubble ─────────────────────── */
.req-bubble {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 16px 16px 16px 4px;
    padding: 12px 16px;
    font-size: 14px;
    line-height: 1.6;
    color: #374151;
    max-width: 520px;
    word-break: break-word;
    overflow-wrap: break-word;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}

/* ── Action bar ─────────────────────────────────────────── */
#req-action-bar {
    padding: 16px 24px;
    background: #fff;
    border-top: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

/* ── Mobile ─────────────────────────────────────────────── */
@media (max-width: 767px) {
    #req-list-panel { width: 100%; max-width: 100%; border-right: none; }
    #req-preview-panel { position: fixed; inset: 64px 0 0 0; z-index: 50; transform: translateX(100%); transition: transform 0.25s cubic-bezier(.4,0,.2,1); }
    #req-preview-panel.mobile-open { transform: translateX(0); }
}

/* ── Empty state ─────────────────────────────────────────── */
#req-empty {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 14px;
    color: #9ca3af;
    padding: 32px;
    text-align: center;
}
</style>

<div id="requests-page">

  <!-- ═══ LEFT: Request List ════════════════════════════ -->
  <div id="req-list-panel">

    <!-- Header -->
    <div class="px-4 pt-5 pb-4 border-b border-gray-100 flex-shrink-0">
      <div class="flex items-center gap-3 mb-1">
        <a href="<?= APP_URL ?>/messages"
           class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition flex-shrink-0" title="Back to messages">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
          </svg>
        </a>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight leading-tight">Message Requests</h1>
          <p class="text-xs text-gray-400 mt-0.5">
            <?php if (!empty($requests)): ?>
              <?= count($requests) ?> pending request<?= count($requests) !== 1 ? 's' : '' ?>
            <?php else: ?>
              No pending requests
            <?php endif; ?>
          </p>
        </div>
      </div>

      <?php if (!empty($requests)): ?>
      <div class="mt-3 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2.5 flex items-start gap-2">
        <svg class="h-4 w-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-xs text-amber-700 leading-relaxed">These are messages from people you haven't spoken with. Accept to start chatting, or decline to remove.</p>
      </div>
      <?php endif; ?>
    </div>

    <!-- Request list -->
    <div class="flex-1 overflow-y-auto" id="req-list-scroll">
      <?php if (!empty($requests)): ?>
        <?php foreach ($requests as $req): ?>
        <div class="req-card <?= (($_GET['preview'] ?? '') == $req['other_user_id']) ? 'active' : '' ?>"
             id="req-card-<?= $req['other_user_id'] ?>"
             onclick="openReqPreview(<?= (int)$req['other_user_id'] ?>)">

          <!-- Avatar with amber dot -->
          <div class="relative flex-shrink-0">
            <img src="<?= e(get_profile_picture_url($req['profile_picture'] ?? 'default.png', $req['first_name'], $req['last_name'])) ?>"
                 alt="<?= e($req['first_name']) ?>"
                 class="h-12 w-12 rounded-full object-cover border border-gray-100 shadow-sm">
            <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 bg-amber-400 rounded-full border-2 border-white"></span>
          </div>

          <!-- Info — exactly like inbox convo row -->
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate">
                  <?= e($req['first_name'] . ' ' . $req['last_name']) ?>
                </p>
                <?php if (!empty($req['is_verified'])): ?>
                <svg class="h-3.5 w-3.5 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <?php endif; ?>
              </div>
              <span class="text-[11px] text-gray-400 flex-shrink-0"><?= reqTimeAgo($req['last_message_at']) ?></span>
            </div>
            <p class="text-xs text-gray-500 truncate mt-0.5"><?= e(mb_substr($req['last_message'] ?? '', 0, 55)) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="flex flex-col items-center justify-center h-full py-20 px-8 text-center">
          <div class="h-16 w-16 bg-amber-50 rounded-2xl flex items-center justify-center mb-4">
            <svg class="h-8 w-8 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
          </div>
          <p class="text-base font-bold text-gray-600">All caught up!</p>
          <p class="text-sm text-gray-400 mt-1 max-w-[200px]">No pending message requests right now.</p>
          <a href="<?= APP_URL ?>/messages"
             class="mt-5 px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition">
            ← Back to Messages
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div><!-- /req-list-panel -->


  <!-- ═══ RIGHT: Message Preview ═══════════════════════ -->
  <div id="req-preview-panel">

    <?php
    // If a preview user is requested, load their messages
    $previewUserId = $_GET['preview'] ?? null;
    $previewUser   = null;
    $previewMsgs   = [];
    $previewConvo  = null;

    if ($previewUserId) {
        foreach ($requests as $r) {
            if ($r['other_user_id'] == $previewUserId) {
                $previewUser  = $r;
                $previewConvo = ['id' => $r['conversation_id']];
                break;
            }
        }
        if ($previewUser) {
            $msgModel    = new \App\Models\Message();
            $convo       = $msgModel->getConversationBetween($_SESSION['user_id'], $previewUserId);
            if ($convo) {
                $previewMsgs = $msgModel->getMessagesByConversation($convo['id']);
                $previewConvo = $convo;
            }
        }
    }
    ?>

    <?php if ($previewUser): ?>

    <!-- Header -->
    <div class="flex items-center gap-3 px-5 py-3 bg-white border-b border-gray-100 flex-shrink-0">
      <!-- Mobile back -->
      <button class="md:hidden p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 transition" onclick="closeMobileReq()">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <!-- Avatar -->
      <div class="relative flex-shrink-0">
        <img src="<?= e(get_profile_picture_url($previewUser['profile_picture'] ?? 'default.png', $previewUser['first_name'], $previewUser['last_name'])) ?>"
             class="h-10 w-10 rounded-full object-cover border border-amber-200">
        <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-amber-400 rounded-full border-2 border-white"></span>
      </div>
      <!-- Name -->
      <div class="flex-1 min-w-0">
        <p class="text-sm font-bold text-gray-900 truncate"><?= e($previewUser['first_name'] . ' ' . $previewUser['last_name']) ?></p>
        <p class="text-xs text-amber-600 font-semibold">Wants to message you</p>
      </div>
      <!-- View profile -->
      <a href="<?= APP_URL ?>/profile/<?= e($previewUser['username'] ?? '') ?>"
         class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition flex-shrink-0" title="View profile">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
      </a>
    </div>

    <!-- Messages (read-only preview) -->
    <div class="flex-1 overflow-y-auto px-6 py-5 space-y-3" id="req-msgs">
      <?php if (empty($previewMsgs)): ?>
      <div class="flex flex-col items-center justify-center h-full gap-3 text-gray-400">
        <p class="text-sm">No messages yet</p>
      </div>
      <?php else: ?>
        <?php
        $prevDate = null;
        foreach ($previewMsgs as $msg):
            $isMe     = ($msg['sender_id'] == $_SESSION['user_id']);
            $time     = date('g:i A', strtotime($msg['created_at']));
            $dateLabel = (function($dt) {
                $diff = time() - strtotime($dt);
                if ($diff < 86400)  return 'Today';
                if ($diff < 172800) return 'Yesterday';
                if ($diff < 604800) return date('l', strtotime($dt));
                return date('M j, Y', strtotime($dt));
            })($msg['created_at']);
            if ($dateLabel !== $prevDate):
                $prevDate = $dateLabel;
        ?>
        <div class="flex justify-center my-2">
          <span class="bg-gray-200 text-gray-500 text-[11px] font-semibold px-3 py-1 rounded-full"><?= e($dateLabel) ?></span>
        </div>
        <?php endif; ?>

        <?php if ($isMe): ?>
        <div class="flex justify-end">
          <div style="max-width:65%">
            <div style="background:#4f46e5;color:#fff;border-radius:18px 18px 4px 18px;padding:9px 14px;font-size:14px;line-height:1.55;word-break:break-word;display:inline-block;min-width:48px;">
              <?= nl2br(e($msg['message_body'])) ?>
            </div>
            <p class="text-[10px] text-gray-400 text-right mt-1 px-1"><?= $time ?></p>
          </div>
        </div>
        <?php else: ?>
        <div class="flex items-end gap-2">
          <img src="<?= e(get_profile_picture_url($msg['profile_picture'] ?? 'default.png', $msg['first_name'], $msg['last_name'])) ?>"
               class="h-7 w-7 rounded-full object-cover flex-shrink-0 border border-gray-100">
          <div style="max-width:65%">
            <div class="req-bubble"><?= nl2br(e($msg['message_body'])) ?></div>
            <p class="text-[10px] text-gray-400 mt-1 px-1"><?= $time ?></p>
          </div>
        </div>
        <?php endif; ?>

        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Action bar -->
    <div id="req-action-bar">
      <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-gray-800">
          Accept request from <span class="text-indigo-600"><?= e($previewUser['first_name']) ?></span>?
        </p>
        <p class="text-xs text-gray-400 mt-0.5">Once accepted you can reply and the conversation moves to your inbox.</p>
      </div>
      <button onclick="doAccept(<?= (int)$previewUser['other_user_id'] ?>, '<?= e(addslashes($previewUser['username'])) ?>')"
              class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition shadow-sm flex-shrink-0">
        Accept
      </button>
      <button onclick="promptDecline(<?= (int)$previewUser['other_user_id'] ?>, '<?= e(addslashes($previewUser['first_name'])) ?>')"
              class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition flex-shrink-0">
        Decline
      </button>
    </div>

    <?php else: ?>
    <!-- Nothing selected -->
    <div id="req-empty">
      <div class="h-20 w-20 bg-amber-50 rounded-3xl flex items-center justify-center">
        <svg class="h-10 w-10 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
      </div>
      <div>
        <p class="font-semibold text-gray-600">Select a request</p>
        <p class="text-sm text-gray-400 mt-1">Click a request from the list to preview it</p>
      </div>
    </div>
    <?php endif; ?>

  </div><!-- /req-preview-panel -->
</div><!-- /requests-page -->


<!-- ── Decline Confirm Modal ─────────────────────────── -->
<div id="decline-modal" class="fixed inset-0 z-[60] hidden">
  <div class="fixed inset-0 bg-black/40" onclick="hideDeclineModal()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 relative z-10">
      <div class="flex items-start gap-3 mb-5">
        <div class="h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
          <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-gray-900 text-base">Decline request?</p>
          <p class="text-sm text-gray-500 mt-1" id="decline-msg-text">This will remove the request. The sender won't be notified.</p>
        </div>
      </div>
      <div class="flex gap-3">
        <button onclick="hideDeclineModal()"
                class="flex-1 px-4 py-2.5 border border-gray-200 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-50 transition">
          Cancel
        </button>
        <button onclick="confirmDecline()"
                class="flex-1 px-4 py-2.5 bg-red-500 text-white text-sm font-bold rounded-xl hover:bg-red-600 transition">
          Decline
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ── Toast ─────────────────────────────────────────── -->
<div id="req-toast" style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(20px);background:#1f2937;color:#fff;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:600;opacity:0;transition:all 0.25s;z-index:9999;pointer-events:none;white-space:nowrap;"></div>

<script>
const appUrl    = '<?= APP_URL ?>';
const csrfToken = '<?= $csrfToken ?>';

let _pendingDeclineId   = null;

/* ── Open preview (server-side render via page reload) ─── */
function openReqPreview(userId) {
  window.location.href = appUrl + '/messages/requests?preview=' + userId;
}

/* ── Mobile back ─────────────────────────────────────── */
function closeMobileReq() {
  document.getElementById('req-preview-panel').classList.remove('mobile-open');
  window.history.pushState({}, '', appUrl + '/messages/requests');
}

/* ── Accept ──────────────────────────────────────────── */
async function doAccept(userId, username) {
  try {
    const res  = await fetch(appUrl + '/messages/accept-request', {
      method: 'POST', credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ user_id: userId, csrf_token: csrfToken })
    });
    const data = await res.json();
    if (data.success) {
      showToast('Request accepted!');
      setTimeout(() => {
        window.location.href = appUrl + '/messages/' + username;
      }, 500);
    } else {
      showToast('Something went wrong. Try again.');
    }
  } catch(e) {
    showToast('Network error. Please retry.');
  }
}

/* ── Decline ─────────────────────────────────────────── */
function promptDecline(userId, firstName) {
  _pendingDeclineId = userId;
  const el = document.getElementById('decline-msg-text');
  if (el) el.textContent = 'Decline the request from ' + firstName + '? They won\'t be notified.';
  document.getElementById('decline-modal').classList.remove('hidden');
}
function hideDeclineModal() {
  document.getElementById('decline-modal').classList.add('hidden');
  _pendingDeclineId = null;
}
async function confirmDecline() {
  hideDeclineModal();
  if (!_pendingDeclineId) return;
  const uid = _pendingDeclineId;
  _pendingDeclineId = null;
  try {
    await fetch(appUrl + '/messages/decline-request', {
      method: 'POST', credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ user_id: uid, csrf_token: csrfToken })
    });
    // Remove card with animation
    const card = document.getElementById('req-card-' + uid);
    if (card) {
      card.style.transition = 'opacity 0.25s, transform 0.25s';
      card.style.opacity = '0';
      card.style.transform = 'translateX(-12px)';
      setTimeout(() => card.remove(), 260);
    }
    // If it was the previewed one, clear preview panel
    const url = new URL(window.location.href);
    if (url.searchParams.get('preview') == uid) {
      window.history.pushState({}, '', appUrl + '/messages/requests');
      document.getElementById('req-preview-panel').innerHTML = `
        <div id="req-empty">
          <div class="h-20 w-20 bg-amber-50 rounded-3xl flex items-center justify-center">
            <svg class="h-10 w-10 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
          </div>
          <p class="font-semibold text-gray-600">Request declined</p>
          <p class="text-sm text-gray-400 mt-1">Select another request from the list</p>
        </div>`;
    }
    showToast('Request declined');
  } catch(e) {}
}

/* ── Toast ───────────────────────────────────────────── */
function showToast(msg) {
  const t = document.getElementById('req-toast');
  t.textContent = msg;
  t.style.opacity = '1';
  t.style.transform = 'translateX(-50%) translateY(0)';
  clearTimeout(t._timer);
  t._timer = setTimeout(() => {
    t.style.opacity = '0';
    t.style.transform = 'translateX(-50%) translateY(20px)';
  }, 2400);
}

/* ── Auto-scroll messages to bottom ─────────────────── */
(function() {
  const msgs = document.getElementById('req-msgs');
  if (msgs) msgs.scrollTop = msgs.scrollHeight;

  // Mark active card if preview open
  const url = new URL(window.location.href);
  const preview = url.searchParams.get('preview');
  if (preview) {
    document.getElementById('req-preview-panel').classList.add('mobile-open');
  }
})();
</script>