<?php
/* ── Helpers ──────────────────────────────────────────── */
function msgTimeAgo($dt) {
    if (!$dt) return '';
    $d = time() - strtotime($dt);
    if ($d < 60)     return 'now';
    if ($d < 3600)   return floor($d/60).'m';
    if ($d < 86400)  return floor($d/3600).'h';
    if ($d < 604800) return date('D', strtotime($dt));
    return date('M j', strtotime($dt));
}
function msgDateChip($dt) {
    if (!$dt) return '';
    $d = time() - strtotime($dt);
    if ($d < 86400)   return 'Today';
    if ($d < 172800)  return 'Yesterday';
    if ($d < 604800)  return date('l', strtotime($dt));
    return date('M j, Y', strtotime($dt));
}

$me      = $_SESSION['user_id'];
$openUid = $withUserId ?? null;
$openUname = $withUsername ?? null;

$openFolder = 'primary';
if (!empty($openConvo)) {
    if ($openConvo['initiator_id'] == $me) {
        $openFolder = $openConvo['folder_for_initiator'] ?? 'primary';
    } else {
        $openFolder = $openConvo['folder_for_participant'] ?? 'primary';
    }
}

/* ── Group messages: same sender + within 30 min = one group ── */
$grouped = [];
$GAP     = 1800; // 30 min
if (!empty($openMessages)) {
    $prevDate = null; $prevSender = null; $prevTs = 0; $prevTimeChipTs = 0;
    foreach ($openMessages as $i => $msg) {
        $chip = msgDateChip($msg['created_at']);
        if ($chip !== $prevDate) {
            $grouped[] = ['type'=>'date','label'=>$chip];
            $prevDate = $chip; $prevSender = null; $prevTs = 0; $prevTimeChipTs = 0;
        }
        $isMe   = ($msg['sender_id'] == $me);
        $ts     = strtotime($msg['created_at']);
        $gap    = ($ts - $prevTs) > 300; // 5 min for bubble tail clustering

        // 30 minute gap time chip in the middle
        if ($ts - $prevTimeChipTs >= $GAP) {
            $grouped[] = ['type'=>'time','label'=>date('g:i A', $ts)];
            $prevTimeChipTs = $ts;
            // Also reset gap for bubbles
            $gap = true;
        }

        $showAv = (!$isMe && ($prevSender != $msg['sender_id'] || $gap));
        $nextSame = false;
        if (isset($openMessages[$i+1])) {
            $nx = $openMessages[$i+1];
            $nextSame = ($nx['sender_id'] == $msg['sender_id'] && (strtotime($nx['created_at']) - $ts) <= 300);
        }

        $grouped[] = ['type'=>'msg','data'=>$msg,'isMe'=>$isMe,'showAv'=>$showAv,'nextSame'=>$nextSame];
        $prevSender = $msg['sender_id']; $prevTs = $ts;
    }
}
?>
<style>
body{overflow:hidden!important}
body>main{overflow:hidden!important;flex:1;display:flex;flex-direction:column}
body footer{display:none!important}

#msg-shell{display:flex;height:calc(100vh - 64px);overflow:hidden;background:#f8fafc}

#msg-list-panel{width:360px;min-width:280px;max-width:360px;flex-shrink:0;display:flex;flex-direction:column;background:#fff;border-right:1px solid #e5e7eb;height:calc(100vh - 64px)}

#msg-chat-panel{flex:1;min-width:0;display:flex;flex-direction:column;background:#f8fafc;overflow:hidden;height:calc(100vh - 64px)}

/* Conversation rows */
.cr{display:flex;align-items:center;gap:10px;padding:10px 16px;cursor:pointer;border-radius:12px;margin:1px 6px;transition:background .12s;position:relative}
.cr:hover{background:#f1f5f9}
.cr.active{background:#eef2ff}
.cr.unread .cn{font-weight:700;color:#111827}
.cr.unread .cp{color:#374151;font-weight:500}

/* Context menu */
.ctx{position:fixed;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.12);z-index:999;min-width:180px;padding:4px;animation:ctxIn .12s ease-out}
@keyframes ctxIn{from{opacity:0;transform:scale(.95) translateY(-4px)}to{opacity:1;transform:scale(1) translateY(0)}}
.ci{display:flex;align-items:center;gap:9px;padding:8px 12px;border-radius:8px;font-size:13px;font-weight:500;color:#374151;cursor:pointer;transition:background .1s;white-space:nowrap}
.ci:hover{background:#f1f5f9}
.ci.danger{color:#ef4444}
.ci.danger:hover{background:#fef2f2}

/* Bubbles */
.msg-row{position:relative;display:flex;width:100%}
.msg-row:hover .msg-menu-btn{opacity:1;pointer-events:auto}
.bm{background:#4f46e5;color:#fff;border-radius:18px 18px 4px 18px;padding:9px 14px;font-size:14px;line-height:1.55;word-break:break-word;overflow-wrap:break-word;min-width:48px;display:inline-block;box-shadow:0 1px 4px rgba(79,70,229,.18)}
.bt{background:#f1f5f9;color:#111827;border-radius:18px 18px 18px 4px;padding:9px 14px;font-size:14px;line-height:1.55;word-break:break-word;overflow-wrap:break-word;min-width:48px;display:inline-block;box-shadow:0 1px 3px rgba(0,0,0,.06)}
.bm-c{border-radius:18px 4px 4px 18px}
.bt-c{border-radius:4px 18px 18px 4px}
.wm{max-width:65%;display:flex;flex-direction:column;align-items:flex-end;gap:2px;position:relative}
.wt{max-width:65%;display:flex;flex-direction:column;align-items:flex-start;gap:2px;position:relative}
.msg-menu-btn{opacity:0;pointer-events:none;transition:opacity .15s;padding:4px;border-radius:50%;color:#9ca3af;cursor:pointer;flex-shrink:0;margin:0 4px;align-self:center}
.msg-menu-btn:hover{background:#f1f5f9;color:#4b5563}
/* Hide three-dot button entirely on touch devices */
@media(hover:none){
  .msg-menu-btn{display:none!important}
  /* Visual feedback when a message bubble is being long-pressed */
  .msg-body.long-press-active{opacity:0.7;transform:scale(0.97);transition:transform .1s,opacity .1s}
}

/* Message context menu wrapper (different from conversation row ctx) */
.msg-ctx-pop{position:absolute;z-index:100;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.1);padding:4px;min-width:140px;display:none}
.msg-ctx-pop.open{display:block;animation:ctxIn .1s ease-out}
.msg-ctx-header{font-size:11px;color:#9ca3af;text-transform:uppercase;letter-spacing:0.04em;font-weight:600;padding:6px 12px;text-align:center;border-bottom:1px solid #f1f5f9;margin-bottom:4px}


/* Date & Time chips */
.dc{display:flex;align-items:center;justify-content:center;margin:16px 0 8px}
.dc span{background:#e5e7eb;color:#6b7280;font-size:11px;font-weight:600;padding:3px 12px;border-radius:99px;letter-spacing:.03em}
.tc{display:flex;align-items:center;justify-content:center;margin:8px 0 4px}
.tc span{color:#9ca3af;font-size:11px;font-weight:600}

/* Input */
#mia{background:#fff;border-top:1px solid #e5e7eb;padding:12px 16px;display:flex;align-items:flex-end;gap:10px}
#mi{flex:1;border:1.5px solid #e5e7eb;border-radius:22px;padding:9px 16px;font-size:14px;resize:none;max-height:120px;overflow-y:auto;line-height:1.5;outline:none;transition:border-color .15s;background:#f8fafc;scrollbar-width:none;-ms-overflow-style:none}
#mi::-webkit-scrollbar{display:none}
#mi:focus{border-color:#6366f1;background:#fff}
#sb{width:40px;height:40px;border-radius:50%;background:#4f46e5;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s,transform .1s;flex-shrink:0;border:none}
#sb:hover:not(:disabled){background:#4338ca;transform:scale(1.05)}
#sb:disabled{background:#c7d2fe;cursor:default}

/* Tabs */
.itb{flex:1;padding:10px 8px;font-size:13px;font-weight:600;color:#6b7280;background:none;border:none;border-bottom:2px solid transparent;cursor:pointer;transition:color .15s,border-color .15s;white-space:nowrap;display:flex;align-items:center;justify-content:center;gap:6px}
.itb.active{color:#4f46e5;border-bottom-color:#4f46e5}

/* Misc */
#chat-empty{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;color:#9ca3af}
#req-banner{background:#fffbeb;border-bottom:1px solid #fde68a;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-shrink:0}
.pin-mark{display:flex;align-items:center;color:#a5b4fc;margin-left:auto;flex-shrink:0}
#cs{width:100%;background:#f1f5f9;border:none;border-radius:10px;padding:8px 14px 8px 36px;font-size:13px;outline:none;transition:background .15s}
#cs:focus{background:#e0e7ff}
.rmb{display:none;align-items:center;justify-content:center;width:28px;height:28px;border-radius:8px;background:transparent;border:none;color:#9ca3af;cursor:pointer;flex-shrink:0;transition:background .12s,color .12s;padding:0}
.rmb:hover{background:#e0e7ff;color:#4f46e5}
.cr:hover .rmb,.cr.active .rmb{display:flex}
.cr .rmb{display:none}

@media(max-width:767px){
  #msg-list-panel{width:100%;max-width:100%;border-right:none}
  #msg-chat-panel{
    position:fixed;
    inset:0;
    z-index:200;
    transform:translateX(100%);
    transition:transform .25s cubic-bezier(.4,0,.2,1);
    display:flex;
    flex-direction:column;
    background:#f8fafc;
    padding-bottom:env(safe-area-inset-bottom);
  }
  #msg-chat-panel.mobile-open{transform:translateX(0)}
  body.chat-open nav{display:none!important}
  #mia{padding-bottom:max(12px,env(safe-area-inset-bottom))}
}
</style>

<div id="msg-shell">

<!-- ══ LEFT: Conversation List ══════════════════════════════ -->
<div id="msg-list-panel">

  <!-- Header -->
  <div class="px-4 pt-5 pb-3 border-b border-gray-100 flex-shrink-0">
    <div class="flex items-center justify-between mb-3">
      <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Messages</h1>
      <a href="<?= APP_URL ?>/search" title="New conversation" class="p-2 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      </a>
    </div>
    <div class="relative">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" id="cs" placeholder="Search conversations…" oninput="filterConvos(this.value)">
    </div>
  </div>

  <!-- Tabs -->
  <div class="flex border-b border-gray-100 px-2 flex-shrink-0">
    <button class="itb <?= $openFolder==='primary' ? 'active' : '' ?>" id="tab-btn-primary" onclick="switchTab('primary')">
      Primary
      <?php $pu = count(array_filter($primaryConvos, fn($c)=>(int)($c['unread_count']??0)>0)); ?>
      <?php if($pu>0):?><span class="bg-indigo-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"><?=$pu?></span><?php endif;?>
    </button>
    <button class="itb <?= $openFolder==='general' ? 'active' : '' ?>" id="tab-btn-general" onclick="switchTab('general')">
      General
      <?php $gu = count(array_filter($generalConvos, fn($c)=>(int)($c['unread_count']??0)>0)); ?>
      <?php if($gu>0):?><span class="bg-gray-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"><?=$gu?></span><?php endif;?>
    </button>
    <a href="<?= APP_URL ?>/messages/requests" class="itb">
      Requests
      <?php if($requestCount>0):?><span class="bg-amber-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"><?=$requestCount?></span><?php endif;?>
    </a>
  </div>

  <!-- Convo lists -->
  <div class="flex-1 overflow-y-auto pt-1 pb-4">

    <!-- Primary -->
    <div id="tab-primary" class="itc <?= $openFolder==='general' ? 'hidden' : '' ?>">
      <?php if(!empty($primaryConvos)):
        $pinned   = array_values(array_filter($primaryConvos, fn($c)=>!empty($c['is_pinned'])));
        $unpinned = array_values(array_filter($primaryConvos, fn($c)=> empty($c['is_pinned'])));
        if($pinned): ?>
        <p class="px-4 pt-2 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pinned</p>
        <?php foreach($pinned as $c) echo renderRow($c,$openUid,$me);
        if($unpinned): ?><div class="mx-4 my-2 border-t border-gray-100"></div><?php endif;
        endif;
        foreach($unpinned??$primaryConvos as $c) echo renderRow($c,$openUid,$me);
      else: ?>
      <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
        <div class="h-14 w-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-4">
          <svg class="h-7 w-7 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <p class="text-sm font-semibold text-gray-500">No primary messages</p>
        <a href="<?= APP_URL ?>/search" class="mt-3 px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition">Find Craftsmen</a>
      </div>
      <?php endif; ?>
    </div>

    <!-- General -->
    <div id="tab-general" class="itc <?= $openFolder==='primary' ? 'hidden' : '' ?>">
      <?php if(!empty($generalConvos)):
        foreach($generalConvos as $c) echo renderRow($c,$openUid,$me);
      else: ?>
      <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
        <div class="h-14 w-14 bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
          <svg class="h-7 w-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <p class="text-sm font-semibold text-gray-500">No general messages</p>
        <p class="text-xs text-gray-400 mt-1">Move conversations here to keep Primary clean</p>
      </div>
      <?php endif; ?>
    </div>

  </div>
</div><!-- /left -->

<!-- ══ RIGHT: Chat Panel ════════════════════════════════════ -->
<div id="msg-chat-panel" class="<?= $openUid ? 'mobile-open' : '' ?>">

<?php if($openUser): ?>

  <!-- Chat header -->
  <div class="flex items-center gap-3 px-5 py-3 bg-white border-b border-gray-100 flex-shrink-0">
    <button class="md:hidden p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 transition" onclick="closeMobile()">
      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <div class="relative flex-shrink-0">
      <img src="<?= e(get_profile_picture_url($openUser['profile_picture']??'default.png',$openUser['first_name'],$openUser['last_name'])) ?>"
           class="h-10 w-10 rounded-full object-cover border border-gray-200">
    </div>
    <div class="flex-1 min-w-0">
      <div class="flex items-center gap-1.5">
        <a href="<?= APP_URL ?>/profile/<?= e($openUser['username']??'') ?>"
           class="text-sm font-bold text-gray-900 hover:text-indigo-600 transition truncate">
          <?= e($openUser['first_name'].' '.$openUser['last_name']) ?>
        </a>
        <?php if(!empty($openUser['is_verified'])): ?>
        <svg class="h-4 w-4 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <?php endif; ?>
      </div>
      <p class="text-xs text-gray-400 capitalize"><?= e(ucfirst($openUser['role'])) ?></p>
    </div>
    <div class="flex items-center gap-1 flex-shrink-0">
      <a href="<?= APP_URL ?>/profile/<?= e($openUser['username']??'') ?>" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="View profile">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      </a>
      <?php if($openConvo): ?>
      <button onclick="showCtx(event,<?=(int)$openConvo['id']?>,<?=(int)$openUid?>)" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
      </button>
      <?php endif; ?>
    </div>
  </div>

  <!-- Request banner -->
  <?php if($isRequest && $isRecipient && $openConvo): ?>
  <div id="req-banner">
    <div class="flex items-center gap-3 min-w-0">
      <img src="<?= e(get_profile_picture_url($openUser['profile_picture']??'default.png',$openUser['first_name'],$openUser['last_name'])) ?>" class="h-9 w-9 rounded-full object-cover flex-shrink-0">
      <div class="min-w-0">
        <p class="text-sm font-bold text-gray-900"><?= e($openUser['first_name']) ?> wants to message you</p>
        <p class="text-xs text-gray-500 truncate"><?= e(mb_substr(end($openMessages)['message_body']??'',0,60)) ?></p>
      </div>
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
      <button onclick="doAccept(<?=(int)$openConvo['id']?>,<?=(int)$openUid?>)" class="px-4 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition">Accept</button>
      <button onclick="doDeclinePrompt(<?=(int)$openConvo['id']?>,<?=(int)$openUid?>)" class="px-4 py-1.5 bg-white border border-gray-200 text-gray-700 text-xs font-semibold rounded-lg hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition">Decline</button>
    </div>
  </div>
  <?php endif; ?>

  <!-- Messages -->
  <div id="mc" class="flex-1 overflow-y-auto px-5 py-4 space-y-1">
    <?php if(empty($openMessages)): ?>
    <div class="flex flex-col items-center justify-center h-full gap-3 text-center py-12">
      <img src="<?= e(get_profile_picture_url($openUser['profile_picture']??'default.png',$openUser['first_name'],$openUser['last_name'])) ?>" class="h-16 w-16 rounded-full object-cover border-2 border-indigo-100 shadow-sm">
      <p class="font-bold text-gray-800"><?= e($openUser['first_name'].' '.$openUser['last_name']) ?></p>
      <p class="text-xs text-gray-400">Start the conversation below</p>
    </div>
    <?php else:
      foreach($grouped as $item):
        if($item['type']==='date'): ?>
        <div class="dc"><span><?= e($item['label']) ?></span></div>
        <?php elseif($item['type']==='time'): ?>
        <div class="tc"><span><?= e($item['label']) ?></span></div>
        <?php else:
          $msg=$item['data']; $isMe=$item['isMe']; $showAv=$item['showAv'];
          $time=date('g:i A',strtotime($msg['created_at']));
          if($isMe): ?>
          <div class="flex justify-end items-end gap-1 msg-row" data-message-id="<?=$msg['id']?>" data-sender-id="<?=$msg['sender_id']?>" data-ts="<?=strtotime($msg['created_at'])*1000?>">
            <div class="msg-menu-btn" onclick="openMsgMenu(event, <?=$msg['id']?>, '<?=$time?>', true)">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
            </div>
            <div class="wm">
              <div class="bm <?=$item['nextSame']?'bm-c':''?> msg-body"><?=nl2br(e($msg['message_body']))?></div>
            </div>
          </div>
          <?php else: ?>
          <div class="flex items-end gap-1 msg-row" data-message-id="<?=$msg['id']?>" data-sender-id="<?=$msg['sender_id']?>" data-ts="<?=strtotime($msg['created_at'])*1000?>">
            <?php if($showAv): ?>
            <img src="<?= e(get_profile_picture_url($msg['profile_picture']??'default.png',$msg['first_name'],$msg['last_name'])) ?>" class="h-7 w-7 rounded-full object-cover flex-shrink-0 border border-gray-100 shadow-sm">
            <?php else: ?><div class="w-7 flex-shrink-0"></div><?php endif; ?>
            <div class="wt">
              <?php if($showAv):?><span class="text-[11px] font-semibold text-gray-500 px-1"><?=e($msg['first_name'])?></span><?php endif;?>
              <div class="bt <?=$item['nextSame']?'bt-c':''?> msg-body"><?=nl2br(e($msg['message_body']))?></div>
            </div>
            <div class="msg-menu-btn" onclick="openMsgMenu(event, <?=$msg['id']?>, '<?=$time?>', false)">
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
            </div>
          </div>
          <?php endif; endif; endforeach;
    endif; ?>
  </div>

  <!-- Input -->
  <?php if($canSend): ?>
  <div id="mia">
    <textarea id="mi" placeholder="Message…" rows="1" oninput="resize(this)"></textarea>
    <button id="sb" disabled title="Send">
      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
    </button>
  </div>
  <?php elseif($isRequest&&$isRecipient): ?>
  <div class="flex items-center justify-center gap-2 px-5 py-4 bg-white border-t border-gray-100 text-sm text-gray-400">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
    Accept the request above to reply
  </div>
  <?php endif; ?>

<?php else: ?>
  <div id="chat-empty">
    <div class="h-20 w-20 bg-indigo-50 rounded-3xl flex items-center justify-center">
      <svg class="h-10 w-10 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
    </div>
    <div class="text-center">
      <p class="font-semibold text-gray-600">Select a conversation</p>
      <p class="text-sm text-gray-400 mt-1">Choose from the list or start a new chat</p>
    </div>
  </div>
<?php endif; ?>

</div><!-- /right -->
</div><!-- /shell -->

<!-- Context menu -->
<div id="ctx" class="ctx hidden"></div>
<div id="msg-ctx" class="msg-ctx-pop"></div>

<!-- Folder modal -->
<div id="fm" class="fixed inset-0 z-[60] hidden">
  <div class="fixed inset-0 bg-black/40" onclick="hideFM()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xs p-6 relative z-10">
      <h3 class="text-base font-bold text-gray-900 mb-4">Move to folder</h3>
      <div class="space-y-2">
        <button onclick="doFolder('primary')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 transition text-sm font-semibold text-gray-700">
          <span class="h-8 w-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 flex-shrink-0"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></span>
          Primary
        </button>
        <button onclick="doFolder('general')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 hover:border-gray-400 hover:bg-gray-50 transition text-sm font-semibold text-gray-700">
          <span class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 flex-shrink-0"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg></span>
          General
        </button>
      </div>
      <button onclick="hideFM()" class="mt-4 w-full text-center text-sm text-gray-400 hover:text-gray-600 transition">Cancel</button>
    </div>
  </div>
</div>

<!-- Delete modal -->
<div id="dm" class="fixed inset-0 z-[60] hidden">
  <div class="fixed inset-0 bg-black/40" onclick="hideDM()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 relative z-10">
      <div class="flex items-center gap-3 mb-4">
        <div class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
          <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div>
          <p class="font-bold text-gray-900">Delete conversation?</p>
          <p class="text-xs text-gray-500 mt-0.5">Hides it from your inbox. The other person won't be notified.</p>
        </div>
      </div>
      <div class="flex gap-3">
        <button onclick="hideDM()" class="flex-1 px-4 py-2 border border-gray-200 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-50 transition">Cancel</button>
        <button onclick="doDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- Decline modal -->
<div id="dcm" class="fixed inset-0 z-[60] hidden">
  <div class="fixed inset-0 bg-black/40" onclick="hideDCM()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 relative z-10">
      <div class="flex items-center gap-3 mb-4">
        <div class="h-10 w-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
          <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
          <p class="font-bold text-gray-900">Decline request?</p>
          <p class="text-xs text-gray-500 mt-0.5" id="dcm-txt">The sender won't be notified.</p>
        </div>
      </div>
      <div class="flex gap-3">
        <button onclick="hideDCM()" class="flex-1 px-4 py-2 border border-gray-200 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-50 transition">Cancel</button>
        <button onclick="doDecline()" class="flex-1 px-4 py-2 bg-amber-500 text-white text-sm font-bold rounded-xl hover:bg-amber-600 transition">Decline</button>
      </div>
    </div>
  </div>
</div>

<script>
const A  = '<?= APP_URL ?>';
const ME = <?= $me ?>;
const TK = '<?= $_SESSION['csrf_token'] ?>';
let oUid = <?= $openUid ? (int)$openUid : 'null' ?>;
let oUname = '<?= $openUname ? e($openUname) : '' ?>';
let oCid = <?= $openConvo ? (int)$openConvo['id'] : 'null' ?>;
let oFolder = '<?= e($openFolder) ?>';
let lastId = <?= !empty($openMessages) ? (int)end($openMessages)['id'] : 0 ?>;
let accepted = <?= (!$isRequest)?'true':'false' ?>;
let _fc=null,_dc=null,_decUid=null,_decCid=null;
let _msgToDel = null;
const GAP = 300*1000;
const BIG_GAP = 1800*1000;

/* Escape HTML + newlines */
const esc=s=>String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;').replace(/\n/g,'<br>');

/* Fallback avatar — matches PHP helper exactly */
const FB_COLORS=['F87171','FBBF24','34D399','60A5FA','818CF8','A78BFA','F472B6','14B8A6'];
const fbAv=(fn,ln)=>{const n=encodeURIComponent((fn+' '+ln).trim());return'https://ui-avatars.com/api/?name='+n+'&background='+FB_COLORS[n.length%FB_COLORS.length]+'&color=fff&size=256';};

/* Tab switch */
function switchTab(t){
  document.querySelectorAll('.itc').forEach(e=>e.classList.add('hidden'));
  document.querySelectorAll('.itb').forEach(e=>e.classList.remove('active'));
  const el=document.getElementById('tab-'+t); if(el)el.classList.remove('hidden');
  const btn=document.getElementById('tab-btn-'+t); if(btn)btn.classList.add('active');
}

/* Search filter */
function filterConvos(q){
  q=q.toLowerCase();
  document.querySelectorAll('.cr').forEach(r=>{r.style.display=(!q||(r.dataset.name||'').includes(q))?'':'none';});
}

/* Open chat — full navigation (simple, reliable) */
function openChat(username){window.location.href=A+'/messages/'+username;}

/* Mobile back */
function closeMobile(){
  document.getElementById('msg-chat-panel').classList.remove('mobile-open');
  document.body.classList.remove('chat-open');
  window.history.pushState({},'',A+'/messages');
  oUid=null;
}

/* On page load: if arriving at an open chat (from profile/jobboard),
   hide navbar immediately and scroll messages to bottom */
(function(){
  var panel=document.getElementById('msg-chat-panel');
  if(panel&&panel.classList.contains('mobile-open')){
    document.body.classList.add('chat-open');
    requestAnimationFrame(function(){
      var mc=document.getElementById('mc');
      if(mc)mc.scrollTop=mc.scrollHeight;
    });
  }
})();

/* Textarea resize */
function resize(el){el.style.height='auto';el.style.height=Math.min(el.scrollHeight,120)+'px';const b=document.getElementById('sb');if(b)b.disabled=!el.value.trim();}

/* Bind input */
(function(){
  const inp=document.getElementById('mi'), btn=document.getElementById('sb');
  if(!inp)return;
  inp.addEventListener('input',function(){resize(this);if(btn)btn.disabled=!this.value.trim();});
  inp.addEventListener('keydown',e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();send();}});
  if(btn)btn.addEventListener('click',send);
  // Scroll to bottom
  const mc=document.getElementById('mc'); if(mc)mc.scrollTop=mc.scrollHeight;
  // Ensure chat panel opens on mobile when arriving from an external link
    // (profile page "Message" button, job board, etc.)
    // The PHP sets mobile-open on the element, but on slow phones the CSS
    // transition fires before paint — this forces a re-trigger.
    if (oUid) {
      var panel = document.getElementById('msg-chat-panel');
      if (panel && !panel.classList.contains('mobile-open')) {
        panel.classList.add('mobile-open');
      }
      // Also scroll chat to bottom in case messages loaded slowly
      requestAnimationFrame(function() {
        var mc2 = document.getElementById('mc');
        if (mc2) mc2.scrollTop = mc2.scrollHeight;
      });
    }

  // Also replaceState immediately to clean the URL up!
  if (oUname && window.history && window.history.replaceState) {
    // Keep it as ?u=... Wait, the user said "not show the user id", 
    // ?u=username is clean. So we don't *need* to replace state, it stays nice!
  }
})();

/* Send message */
async function send(){
  const inp=document.getElementById('mi'); if(!inp)return;
  const body=inp.value.trim(); if(!body||!oUid)return;
  const btn=document.getElementById('sb'); if(btn)btn.disabled=true;
  const mc=document.getElementById('mc');
  // Remove empty state
  const es=mc?mc.querySelector('.flex.flex-col.items-center'):null; if(es)es.remove();
  // Optimistic bubble
  const tstr=new Date().toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit',hour12:true});
  if(mc){
    // Very simple optimistic render without timestamps under it. Middle timestamp if big gap could be added but skipping to keep optimistic instant.
    mc.insertAdjacentHTML('beforeend',`<div class="flex justify-end items-end gap-1 msg-row" data-sender-id="${ME}" data-ts="${Date.now()}"><div class="msg-menu-btn" onclick="openMsgMenu(event, null, '${tstr}', true)"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg></div><div class="wm"><div class="bm msg-body">${esc(body)}</div></div></div>`);
    mc.scrollTop=mc.scrollHeight;
  }
  inp.value=''; inp.style.height='auto';
  try{
    const r=await fetch(A+'/messages/send',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({receiver_id:oUid,message:body,csrf_token:TK})});
    const d=await r.json();
    if(!d.success)alert(d.message||'Failed to send.');
    updatePreview(oUid,body,'You');
  }catch{alert('Send failed. Please retry.');}
  if(btn)btn.disabled=false;
}

/* Update convo row preview or inject new row */
function updatePreview(uid,body,pfx){
  const rows=document.querySelectorAll('[data-uid="'+uid+'"]');
  if(rows.length){
    rows.forEach(r=>{
      const p=r.querySelector('.cp'); if(p)p.textContent=(pfx?pfx+': ':'')+body.substring(0,55);
      const t=r.querySelector('.cts'); if(t)t.textContent='now';
    });
  } else {
    injectRow(uid,body,pfx);
  }
}

/* Inject new row after first send */
async function injectRow(uid,body,pfx){
  try{
    const r=await fetch(A+'/messages/user-info?user_id='+uid,{credentials:'same-origin',headers:{'Accept':'application/json'}});
    const d=await r.json(); if(!d.success)return;
    const u=d.user, pic=u.pic_url||fbAv(u.first_name,u.last_name), cid=d.conversation_id||0;
    const vbadge=u.is_verified?`<svg class="h-3.5 w-3.5 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`:'';
    const tab=document.getElementById('tab-primary'); if(!tab)return;
    const es=tab.querySelector('.flex.flex-col.items-center'); if(es)es.remove();
    tab.insertAdjacentHTML('afterbegin',`
      <div class="cr active" data-uid="${uid}" data-cid="${cid}" data-name="${esc((u.first_name+' '+u.last_name).toLowerCase())}" data-pinned="0" data-muted="0"
           onclick="openChat('${u.username}')" oncontextmenu="event.preventDefault();showCtx(event,${cid},${uid})">
        <div class="relative flex-shrink-0"><img src="${pic}" class="h-12 w-12 rounded-full object-cover border border-gray-100 shadow-sm" onerror="this.src='${fbAv(u.first_name,u.last_name)}'"></div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-1 min-w-0"><p class="cn text-sm font-bold truncate text-gray-800">${esc(u.first_name+' '+u.last_name)}</p>${vbadge}</div>
            <span class="cts text-[11px] text-gray-400">now</span>
          </div>
          <div class="flex items-center justify-between gap-2 mt-0.5">
            <p class="cp text-xs text-gray-400 truncate">${esc((pfx?pfx+': ':'')+body.substring(0,55))}</p>
            <div class="flex items-center gap-1 flex-shrink-0">
              <button class="rmb" onclick="event.stopPropagation();showCtx(event,${cid},${uid})">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/></svg>
              </button>
            </div>
          </div>
        </div>
      </div>`);
  }catch{}
}

/* Clear unread on row */
function clearUnread(uid){
  document.querySelectorAll('[data-uid="'+uid+'"]').forEach(r=>{
    r.classList.remove('unread');
    const dot=r.querySelector('.udot'); if(dot)dot.remove();
    const badge=r.querySelector('.ubadge'); if(badge)badge.remove();
  });
  rebadgeTabs();
}

/* Recount tab badges */
function rebadgeTabs(){
  ['primary','general'].forEach(t=>{
    const el=document.getElementById('tab-'+t); if(!el)return;
    const n=el.querySelectorAll('.cr.unread').length;
    const btn=document.getElementById('tab-btn-'+t); if(!btn)return;
    const s=btn.querySelector('span'); if(s)s.remove();
    if(n>0){const sp=document.createElement('span');sp.className=t==='primary'?'bg-indigo-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full':'bg-gray-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full';sp.textContent=n;btn.appendChild(sp);}
  });
}

/* Relative time */
function rt(dt){
  const d=new Date(dt.includes('T')?dt:dt+' UTC'), diff=Math.floor((Date.now()-d.getTime())/1000);
  if(diff<60)return'now'; if(diff<3600)return Math.floor(diff/60)+'m'; if(diff<86400)return Math.floor(diff/3600)+'h';
  return['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][d.getDay()];
}

/* Message poll — new messages in open chat */
setInterval(async()=>{
  if(!oUid)return;
  try{
    const r=await fetch(A+'/messages/poll?with='+oUid+'&after='+lastId,{credentials:'same-origin',headers:{'Accept':'application/json'}});
    const d=await r.json(); if(!d.success||!d.messages?.length)return;
    const mc=document.getElementById('mc'); if(!mc)return;
    const atBottom=mc.scrollHeight-mc.scrollTop-mc.clientHeight<60;
    const allPrev=Array.from(mc.querySelectorAll('[data-sender-id]'));
    const prev=allPrev.length?allPrev[allPrev.length-1]:null;
    let pSnd=prev?parseInt(prev.dataset.senderId||0):null, pTs=prev?parseInt(prev.dataset.ts||0):0;

    d.messages.forEach(m=>{
      if(m.sender_id==ME){lastId=Math.max(lastId,m.id);pSnd=m.sender_id;pTs=new Date(m.created_at+' UTC').getTime();return;}
      lastId=Math.max(lastId,m.id);
      const mts=new Date(m.created_at+' UTC').getTime();
      const time=new Date(m.created_at+' UTC').toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit',hour12:true});
      const pic=m.pic_url||fbAv(m.first_name,m.last_name);
      const showAv=(pSnd!=m.sender_id||(mts-pTs)>GAP);
      const av=showAv?`<img src="${pic}" class="h-7 w-7 rounded-full object-cover flex-shrink-0 border border-gray-100 shadow-sm" onerror="this.src='${fbAv(m.first_name,m.last_name)}'">`:
                      `<div class="w-7 flex-shrink-0"></div>`;
      if(!showAv){const bs=mc.querySelectorAll('.bt');const lb=bs.length?bs[bs.length-1]:null;if(lb)lb.classList.add('bt-c');}
      
      // Inject time chip if big gap
      if (mts - pTs >= BIG_GAP && pTs !== 0) {
        mc.insertAdjacentHTML('beforeend',`<div class="tc"><span>${time}</span></div>`);
      }

      mc.insertAdjacentHTML('beforeend',`<div class="flex items-end gap-1 msg-row" data-message-id="${m.id}" data-sender-id="${m.sender_id}" data-ts="${mts}">${av}<div class="wt">${showAv?`<span class="text-[11px] font-semibold text-gray-500 px-1">${esc(m.first_name)}</span>`:''}<div class="bt msg-body">${esc(m.message_body)}</div></div><div class="msg-menu-btn" onclick="openMsgMenu(event, ${m.id}, '${time}', false)"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg></div></div>`);
      pSnd=m.sender_id; pTs=mts;
      updatePreview(oUid,m.message_body,m.first_name);
    });
    if(atBottom)mc.scrollTop=mc.scrollHeight;
    if(d.conversation_status==='accepted'&&!accepted){accepted=true;window.location.reload();}
  }catch{}
},2000);

/* Inbox poll — updates left panel live */
let iPollTs=Math.floor(Date.now()/1000)-5;
setInterval(async()=>{
  try{
    const r=await fetch(A+'/messages/poll-inbox?since='+iPollTs,{credentials:'same-origin',headers:{'Accept':'application/json'}});
    const d=await r.json(); if(!d.success||!d.conversations)return;
    if(d.server_time)iPollTs=d.server_time-1;
    d.conversations.forEach(c=>{
      const uid=c.other_user_id,cid=c.conversation_id,folder=c.folder||'primary';
      const unread=parseInt(c.unread_count||0), muted=parseInt(c.is_muted||0)===1, isOpen=(uid==oUid);
      const pic=c.pic_url||fbAv(c.first_name,c.last_name);
      const rows=document.querySelectorAll('[data-uid="'+uid+'"]');
      if(rows.length){
        rows.forEach(row=>{
          const p=row.querySelector('.cp'); if(p)p.textContent=((c.last_sender_id==ME?'You: ':'')+c.last_message||'').substring(0,55);
          const t=row.querySelector('.cts'); if(t)t.textContent=rt(c.last_message_at||'');
          const dot=row.querySelector('.udot'), badge=row.querySelector('.ubadge');
          if(unread>0&&!muted&&!isOpen){
            row.classList.add('unread');
            const aw=row.querySelector('.relative.flex-shrink-0');
            if(aw&&!dot)aw.insertAdjacentHTML('beforeend','<span class="udot absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 bg-indigo-600 rounded-full border-2 border-white"></span>');
            if(badge)badge.textContent=unread>99?'99+':unread;
            else{const rmb=row.querySelector('.rmb'),b=document.createElement('span');b.className='ubadge h-5 min-w-[20px] px-1.5 bg-indigo-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center';b.textContent=unread>99?'99+':unread;if(rmb&&rmb.parentNode)rmb.parentNode.insertBefore(b,rmb);}
          } else if(isOpen||unread===0){row.classList.remove('unread');if(dot)dot.remove();if(badge)badge.remove();}
          const tab=document.getElementById('tab-'+folder);
          if(tab&&row.parentNode===tab)tab.prepend(row);
        });
      } else if(c.conversation_status==='accepted'){
        const vb=c.is_verified?`<svg class="h-3.5 w-3.5 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`:'';
        const ud=unread>0&&!muted?'<span class="udot absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 bg-indigo-600 rounded-full border-2 border-white"></span>':'';
        const ub=unread>0&&!muted?`<span class="ubadge h-5 min-w-[20px] px-1.5 bg-indigo-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center">${unread>99?'99+':unread}</span>`:'';
        const tab=document.getElementById('tab-'+folder); if(!tab)return;
        const es=tab.querySelector('.flex.flex-col.items-center'); if(es)es.remove();
        tab.insertAdjacentHTML('afterbegin',`
          <div class="cr ${unread>0&&!muted?'unread':''}" data-uid="${uid}" data-cid="${cid}" data-name="${esc((c.first_name+' '+c.last_name).toLowerCase())}" data-pinned="0" data-muted="${muted?1:0}"
               onclick="openChat('${c.username}')" oncontextmenu="event.preventDefault();showCtx(event,${cid},${uid})">
            <div class="relative flex-shrink-0"><img src="${pic}" class="h-12 w-12 rounded-full object-cover border border-gray-100 shadow-sm" onerror="this.src='${fbAv(c.first_name,c.last_name)}'">${ud}</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1 min-w-0"><p class="cn text-sm ${unread>0?'font-bold':'font-medium'} truncate text-gray-800">${esc(c.first_name+' '+c.last_name)}</p>${vb}</div>
                <span class="cts text-[11px] text-gray-400">${rt(c.last_message_at||'')}</span>
              </div>
              <div class="flex items-center justify-between gap-2 mt-0.5">
                <p class="cp text-xs ${unread>0?'text-gray-700 font-medium':'text-gray-400'} truncate">${esc(((c.last_sender_id==ME?'You: ':'')+c.last_message||'').substring(0,55))}</p>
                <div class="flex items-center gap-1 flex-shrink-0">${ub}<button class="rmb" onclick="event.stopPropagation();showCtx(event,${cid},${uid})"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/></svg></button></div>
              </div>
            </div>
          </div>`);
      }
      rebadgeTabs();
    });
  }catch{}
},3000);

/* Context menu */
function showCtx(ev,cid,uid){
  ev.stopPropagation(); _fc=cid; _dc=cid;
  const row=document.querySelector('[data-uid="'+uid+'"]');
  const pinned=row?row.dataset.pinned==='1':false, muted=row?row.dataset.muted==='1':false;
  const m=document.getElementById('ctx');
  m.innerHTML=`
    <div class="ci" onclick="doPin(${cid},${uid})"><svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>${pinned?'Unpin':'Pin'}</div>
    <div class="ci" onclick="doMute(${cid},${uid})"><svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="${muted?'M15.536 8.464a5 5 0 010 7.072M12 6v12m0 0L8 14m4 4l4-4':'M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2'}"/></svg>${muted?'Unmute':'Mute'}</div>
    <div class="ci" onclick="doMarkRead(${cid})"><svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Mark as read</div>
    <div class="ci" onclick="showFM(${cid})"><svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>Move to folder</div>
    <div style="height:1px;background:#f1f5f9;margin:4px 0"></div>
    <div class="ci danger" onclick="showDM(${cid})"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Delete</div>`;
  m.classList.remove('hidden');
  const x=Math.min(ev.clientX,window.innerWidth-200), y=(ev.clientY+220>window.innerHeight)?ev.clientY-220:ev.clientY;
  m.style.left=Math.max(8,x)+'px'; m.style.top=Math.max(8,y)+'px';
}
document.addEventListener('click',(e)=>{
  document.getElementById('ctx').classList.add('hidden');
  document.getElementById('msg-ctx').classList.remove('open');
});
document.addEventListener('contextmenu',e=>{const r=e.target.closest('.cr[data-uid]');if(r){e.preventDefault();showCtx(e,parseInt(r.dataset.cid||0),parseInt(r.dataset.uid||0));}});

/* Message Menu (three dots) */
function openMsgMenu(ev, msgId, timeStr, isMe) {
  ev.stopPropagation();
  // Get text content of this specific message row
  let row = ev.currentTarget.closest('.msg-row');
  let body = row ? row.querySelector('.msg-body').innerText : '';
  
  const m = document.getElementById('msg-ctx');
  let html = `<div class="msg-ctx-header">${timeStr}</div>`;
  html += `<div class="ci" onclick="copyMsgText('${esc(body.replace(/'/g, "\\'"))}')"><svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>Copy</div>`;
  if (isMe && msgId) {
     html += `<div class="ci danger" onclick="deleteMessage(${msgId})"><svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Delete</div>`;
  }
  m.innerHTML = html;
  m.classList.add('open');
  
  let x = ev.clientX;
  let y = ev.clientY;
  
  // Calculate height of the menu approximately (header + 1 or 2 items)
  let menuHeight = isMe ? 120 : 80; // approximate height in pixels
  
  if (isMe) {
     x = x - 150; // Pop open to the left if it's our message
  } else {
     x = x + 10;
  }
  
  // Try to open ABOVE the cursor by subtracting the menu height + some padding
  y = y - menuHeight - 10;
  
  // If opening above goes off the top of the screen, open below instead
  if (y < 64) { // 64 is approx header height
     y = ev.clientY + 20;
  }
  
  m.style.left = x + 'px';
  m.style.top = y + 'px';
}

function copyMsgText(txt) {
  navigator.clipboard.writeText(txt);
  toast('Copied');
}

// Minimal delete for individual message
async function deleteMessage(id) {
  if(!id)return;
  _msgToDel = id;
  try{
     const r = await fetch(A+'/messages/delete-message', {
         method: 'POST',
         credentials: 'same-origin',
         headers: { 'Content-Type': 'application/json' },
         body: JSON.stringify({ message_id: id, csrf_token: TK })
     });
     const d = await r.json();
     if (d.success) {
         document.querySelectorAll(`[data-message-id="${id}"]`).forEach(r=>{r.style.transition='opacity .2s';r.style.opacity='0';setTimeout(()=>r.remove(),200);});
         toast('Message deleted');
     } else {
         toast(d.message || 'Failed to delete');
     }
  }catch{
     toast('Network error');
  }
}

/* Pin */
async function doPin(cid,uid){document.getElementById('ctx').classList.add('hidden');try{const r=await fetch(A+'/messages/pin',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({conversation_id:cid,csrf_token:TK})});const d=await r.json();if(d.success)window.location.reload();}catch{}}

/* Mute */
async function doMute(cid,uid){document.getElementById('ctx').classList.add('hidden');try{const r=await fetch(A+'/messages/mute',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({conversation_id:cid,csrf_token:TK})});const d=await r.json();if(d.success){const rows=document.querySelectorAll('[data-uid="'+uid+'"]');rows.forEach(r=>r.dataset.muted=d.is_muted?'1':'0');toast(d.is_muted?'Muted':'Unmuted');}}catch{}}

/* Mark read */
async function doMarkRead(cid){document.getElementById('ctx').classList.add('hidden');try{await fetch(A+'/messages/mark-read',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({conversation_id:cid,csrf_token:TK})});document.querySelectorAll('[data-cid="'+cid+'"]').forEach(r=>{r.classList.remove('unread');const dot=r.querySelector('.udot');if(dot)dot.remove();const b=r.querySelector('.ubadge');if(b)b.remove();});rebadgeTabs();toast('Marked as read');}catch{}}

/* Folder */
function showFM(cid){_fc=cid;document.getElementById('ctx').classList.add('hidden');document.getElementById('fm').classList.remove('hidden');}
function hideFM(){document.getElementById('fm').classList.add('hidden');}
async function doFolder(f){hideFM();if(!_fc)return;try{await fetch(A+'/messages/folder',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({conversation_id:_fc,folder:f,csrf_token:TK})});toast('Moved to '+f[0].toUpperCase()+f.slice(1));setTimeout(()=>window.location.reload(),600);}catch{}}

/* Delete */
function showDM(cid){_dc=cid;document.getElementById('ctx').classList.add('hidden');document.getElementById('dm').classList.remove('hidden');}
function hideDM(){document.getElementById('dm').classList.add('hidden');}
async function doDelete(){hideDM();if(!_dc)return;try{await fetch(A+'/messages/delete',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({conversation_id:_dc,csrf_token:TK})});if(oCid&&oCid==_dc){window.location.href=A+'/messages';return;}document.querySelectorAll('[data-cid="'+_dc+'"]').forEach(r=>{r.style.transition='opacity .3s';r.style.opacity='0';setTimeout(()=>r.remove(),300);});toast('Deleted');}catch{}}

/* Accept / Decline request */
async function doAccept(cid,uid){try{const r=await fetch(A+'/messages/accept-request',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({user_id:uid,csrf_token:TK})});const d=await r.json();if(d.success)window.location.reload();}catch{}}
function doDeclinePrompt(cid,uid){_decCid=cid;_decUid=uid;document.getElementById('dcm').classList.remove('hidden');}
function hideDCM(){document.getElementById('dcm').classList.add('hidden');_decCid=null;_decUid=null;}
async function doDecline(){hideDCM();if(!_decUid)return;try{await fetch(A+'/messages/decline-request',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({user_id:_decUid,csrf_token:TK})});window.location.href=A+'/messages';}catch{}}

/* Toast */
function toast(msg){let t=document.getElementById('tst');if(!t){t=document.createElement('div');t.id='tst';t.style.cssText='position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(20px);background:#1f2937;color:#fff;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:600;opacity:0;transition:all .25s;z-index:9999;pointer-events:none;';document.body.appendChild(t);}t.textContent=msg;t.style.opacity='1';t.style.transform='translateX(-50%) translateY(0)';clearTimeout(t._t);t._t=setTimeout(()=>{t.style.opacity='0';t.style.transform='translateX(-50%) translateY(20px)';},2200);}

/* Init */
(()=>{
  // Tab is already correct from server-side HTML — just scroll chat to bottom
  const mc=document.getElementById('mc'); if(mc)mc.scrollTop=mc.scrollHeight;
})();
</script>

<?php
function renderRow($c,$openUid,$me){
    $unread  = (int)($c['unread_count']??0);
    $isActive= ($openUid==$c['other_user_id']);
    $isPinned= !empty($c['is_pinned']);
    $isMuted = !empty($c['is_muted']);
    $pic     = e(get_profile_picture_url($c['profile_picture']??'default.png',$c['first_name'],$c['last_name']));
    $name    = e($c['first_name'].' '.$c['last_name']);
    $pfx     = ($c['last_sender_id']==$me)?'You: ':'';
    $prev    = e($pfx.mb_substr($c['last_message']??'',0,55));
    $ts      = msgTimeAgo($c['last_message_at']??'');
    $cid     = (int)$c['conversation_id'];
    $uid     = (int)$c['other_user_id'];
    $uname   = e($c['username']);
    $isVerif = !empty($c['is_verified']);
    $cls     = 'cr'.($unread?' unread':'').($isActive?' active':'');
    ob_start(); ?>
    <div class="<?=$cls?>" data-uid="<?=$uid?>" data-cid="<?=$cid?>" data-name="<?=strtolower($c['first_name'].' '.$c['last_name'])?>" data-pinned="<?=$isPinned?1:0?>" data-muted="<?=$isMuted?1:0?>"
         onclick="openChat('<?=$uname?>')" oncontextmenu="event.preventDefault();showCtx(event,<?=$cid?>,<?=$uid?>)">
      <div class="relative flex-shrink-0">
        <img src="<?=$pic?>" alt="<?=$name?>" class="h-12 w-12 rounded-full object-cover border border-gray-100 shadow-sm">
        <?php if($unread&&!$isMuted):?><span class="udot absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 bg-indigo-600 rounded-full border-2 border-white"></span><?php endif;?>
      </div>
      <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between gap-2">
          <div class="flex items-center gap-1 min-w-0">
            <p class="cn text-sm truncate text-gray-800"><?=$name?></p>
            <?php if($isVerif):?><svg class="h-3.5 w-3.5 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg><?php endif;?>
            <?php if($isMuted):?><svg class="h-3.5 w-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/></svg><?php endif;?>
          </div>
          <span class="cts text-[11px] text-gray-400 flex-shrink-0"><?=e($ts)?></span>
        </div>
        <div class="flex items-center justify-between gap-2 mt-0.5">
          <p class="cp text-xs text-gray-400 truncate"><?=$prev?></p>
          <div class="flex items-center gap-1 flex-shrink-0">
            <?php if($unread&&!$isMuted):?><span class="ubadge h-5 min-w-[20px] px-1.5 bg-indigo-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center"><?=$unread>99?'99+':$unread?></span><?php endif;?>
            <button class="rmb" onclick="event.stopPropagation();showCtx(event,<?=$cid?>,<?=$uid?>)">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/></svg>
            </button>
          </div>
        </div>
      </div>
      <?php if($isPinned):?><div class="pin-mark"><svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg></div><?php endif;?>
    </div>
    <?php return ob_get_clean();
}
?>