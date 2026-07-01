<body>

<div id="splash" class="hidden">
  <div id="splash-sparks"></div>
  <div id="splash-content">
    <div id="splash-icon">⚒️</div>
    <div id="splash-title">Object<span> Workshop</span></div>
    <div id="splash-status">Preparing forge…</div>
    <div id="splash-bar"><div id="splash-fill"></div></div>
  </div>
</div>

<div id="saveDialog" style="display:none;position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div style="background:rgba(20,20,35,0.95);border:1px solid rgba(255,255,255,0.1);border-radius:8px;padding:2rem;min-width:300px;text-align:center;">
    <h3 style="color:#fff;font-weight:300;letter-spacing:1px;margin:0 0 1rem 0;font-size:1.1rem;">Save Scene</h3>
    <input id="saveNameInput" type="text" placeholder="Enter save name..." maxlength="255"
      style="width:100%;padding:0.5rem 0.8rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.15);border-radius:4px;color:#fff;font-size:0.85rem;outline:none;box-sizing:border-box;">
    <div style="margin-top:1rem;display:flex;gap:0.5rem;justify-content:center;">
      <button id="saveDialogConfirm" style="background:rgba(100,140,255,0.2);border:1px solid rgba(100,140,255,0.3);color:#88aaff;padding:0.4rem 1.5rem;border-radius:4px;font-size:0.8rem;cursor:pointer;letter-spacing:1px;">Save</button>
      <button id="saveDialogCancel" style="background:transparent;border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.4);padding:0.4rem 1.5rem;border-radius:4px;font-size:0.8rem;cursor:pointer;letter-spacing:1px;">Cancel</button>
    </div>
  </div>
</div>

<div id="textureModal">
  <div class="panel">
    <h3>Select Texture</h3>
    <div id="textureGrid"></div>
    <button id="textureModalClose" style="background:transparent;border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.4);padding:0.4rem 1.5rem;border-radius:4px;font-size:0.8rem;cursor:pointer;letter-spacing:1px;">Close</button>
  </div>
</div>

<div id="upgradeModal" style="display:none;position:fixed;inset:0;z-index:60;background:rgba(0,0,0,0.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
  <div style="background:rgba(18,18,32,0.96);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:2.5rem;max-width:480px;width:90%;text-align:center;max-height:90vh;overflow-y:auto;">
    <div style="font-size:3rem;margin-bottom:0.5rem;" id="upgradeIcon">🆓</div>
    <h3 style="color:#fff;font-weight:400;letter-spacing:0.5px;margin:0 0 0.25rem 0;font-size:1.2rem;" id="upgradeTitle">Trial Plan</h3>
    <p style="color:rgba(255,255,255,0.45);font-size:0.85rem;margin-bottom:1.5rem;line-height:1.5;" id="upgradeDesc">
      Your current plan allows 5 sessions / 5 objects per scene.
    </p>
    <div style="margin-bottom:1.5rem;padding:0.75rem;background:rgba(255,200,50,0.08);border:1px solid rgba(255,200,50,0.15);border-radius:8px;">
      <p style="color:#fbbf24;font-size:0.8rem;font-weight:500;" id="upgradeLimitMsg">You've reached the limit.</p>
    </div>
    <div id="upgradePlansList" style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1.5rem;"></div>
    <button id="upgradeClose" style="background:transparent;border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.35);padding:0.4rem 1.5rem;border-radius:6px;font-size:0.8rem;cursor:pointer;letter-spacing:0.5px;transition:color 0.2s,border-color 0.2s;"
      onmouseover="this.style.color='rgba(255,255,255,0.7)';this.style.borderColor='rgba(255,255,255,0.25)'" onmouseout="this.style.color='rgba(255,255,255,0.35)';this.style.borderColor='rgba(255,255,255,0.12)'">Close</button>
  </div>
</div>

<div id="renewModal" style="display:none;position:fixed;inset:0;z-index:59;background:rgba(0,0,0,0.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
  <div style="background:rgba(18,18,32,0.96);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:2.5rem;max-width:400px;width:90%;text-align:center;">
    <div style="font-size:3rem;margin-bottom:0.5rem;">⏰</div>
    <h3 style="color:#fff;font-weight:400;letter-spacing:0.5px;margin:0 0 0.5rem 0;font-size:1.15rem;" id="renewTitle">Plan Expired</h3>
    <p style="color:rgba(255,255,255,0.45);font-size:0.85rem;margin-bottom:1.5rem;line-height:1.5;" id="renewDesc">Your previous plan has expired. You can still use it, but would you like to renew for another month?</p>
    <div style="display:flex;gap:0.75rem;justify-content:center;">
      <button id="renewConfirm" style="background:rgba(100,140,255,0.2);border:1px solid rgba(100,140,255,0.3);color:#88aaff;padding:0.5rem 1.5rem;border-radius:6px;font-size:0.85rem;cursor:pointer;letter-spacing:0.5px;transition:background 0.2s;"
        onmouseover="this.style.background='rgba(100,140,255,0.3)'" onmouseout="this.style.background='rgba(100,140,255,0.2)'">Renew for Another Month</button>
      <button id="renewDismiss" style="background:transparent;border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.35);padding:0.5rem 1.5rem;border-radius:6px;font-size:0.85rem;cursor:pointer;letter-spacing:0.5px;">Not Now</button>
    </div>
  </div>
</div>

<div id="requestModal" style="display:none;position:fixed;inset:0;z-index:61;background:rgba(0,0,0,0.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
  <div style="background:rgba(18,18,32,0.96);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:2.5rem;max-width:420px;width:90%;text-align:center;">
    <h3 style="color:#fff;font-weight:400;letter-spacing:0.5px;margin:0 0 1rem 0;font-size:1.1rem;">Request Upgrade</h3>
    <p style="color:rgba(255,255,255,0.4);font-size:0.8rem;margin-bottom:1.25rem;" id="requestPlanLabel">You selected: <strong style="color:#fff;"></strong></p>
    <input id="reqName" type="text" placeholder="Your full name" value="{{ auth()->user()->name ?? '' }}" style="width:100%;padding:0.55rem 0.8rem;margin-bottom:0.6rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.12);border-radius:6px;color:#fff;font-size:0.85rem;outline:none;box-sizing:border-box;">
    <input id="reqEmail" type="email" placeholder="Your email" value="{{ auth()->user()->email ?? '' }}" style="width:100%;padding:0.55rem 0.8rem;margin-bottom:0.6rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.12);border-radius:6px;color:#fff;font-size:0.85rem;outline:none;box-sizing:border-box;">
    <input id="reqPhone" type="tel" placeholder="Phone number" style="width:100%;padding:0.55rem 0.8rem;margin-bottom:1rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.12);border-radius:6px;color:#fff;font-size:0.85rem;outline:none;box-sizing:border-box;">
    <div id="reqError" style="color:#f87171;font-size:0.75rem;margin-bottom:0.5rem;display:none;"></div>
    <div style="display:flex;gap:0.5rem;justify-content:center;">
      <button id="reqSubmit" style="background:rgba(100,140,255,0.2);border:1px solid rgba(100,140,255,0.3);color:#88aaff;padding:0.4rem 1.5rem;border-radius:6px;font-size:0.8rem;cursor:pointer;letter-spacing:0.5px;">Submit Request</button>
      <button id="reqCancel" style="background:transparent;border:1px solid rgba(255,255,255,0.12);color:rgba(255,255,255,0.35);padding:0.4rem 1.5rem;border-radius:6px;font-size:0.8rem;cursor:pointer;letter-spacing:0.5px;">Cancel</button>
    </div>
  </div>
</div>

<div id="blocker">
  <div id="sessions-dropdown" class="hidden">
    <button class="toggle" id="sessionsToggle">Sessions ▾</button>
    <a href="{{ route('user.dashboard') }}" class="toggle" style="display:inline-block;text-decoration:none;margin-left:0.3rem;">Dashboard</a>
    <button class="toggle" id="hudSaveBtn" style="display:none;">Save</button>
    <span id="hudSaveStatus" style="display:none;font-size:0.65rem;color:rgba(255,255,255,0.3);margin-left:0.5rem;">Saved</span>
  <div class="list" id="sessionsList"></div>
</div>
  <div id="blocker-content">
    <div id="dropzone">
      <h2>Upload 3D Model</h2>
      <p class="sub">Drag &amp; drop .obj and .mtl files here</p>
      <p class="hint">or click to browse</p>
      <input type="file" id="fileInput" accept=".obj,.mtl,.png" multiple hidden>
    </div>
    <div id="instructions">
      <h1>Room Walkthrough</h1>
      <p class="sub">Explore your 3D space</p>
      <div class="btn">Click to enter</div>
        <div style="margin-top:1.5rem;text-align:center;">
            <a href="{{ route('landing') }}" style="display:inline-block;background:none;border:1px solid rgba(255,255,255,0.25);color:rgba(255,255,255,0.6);padding:0.35rem 1.2rem;border-radius:4px;font-size:0.75rem;cursor:pointer;letter-spacing:1px;text-decoration:none;transition:color 0.2s,border-color 0.2s;"
               onmouseover="this.style.color='rgba(255,255,255,0.9)';this.style.borderColor='rgba(255,255,255,0.4)'" onmouseout="this.style.color='rgba(255,255,255,0.6)';this.style.borderColor='rgba(255,255,255,0.25)'">Landing Page</a>
        </div>
      <div class="keys" id="instr-keys">
        <span id="instr-loading" style="opacity:0.5">Loading shortcuts…</span>
          <div style="margin-top:1.5rem;display:flex;gap:0.75rem;justify-content:center;">
            <button id="saveBtn" style="display:none;background:none;border:1px solid rgba(255,255,255,0.25);color:rgba(255,255,255,0.6);padding:0.35rem 1.2rem;border-radius:4px;font-size:0.75rem;cursor:pointer;letter-spacing:1px;transition:color 0.2s,border-color 0.2s;"
              onmouseover="this.style.color='rgba(255,255,255,0.9)';this.style.borderColor='rgba(255,255,255,0.4)'"
              onmouseout="this.style.color='rgba(255,255,255,0.6)';this.style.borderColor='rgba(255,255,255,0.25)'">Save Scene</button>
            <span id="saveStatus" style="font-size:0.7rem;opacity:0;color:rgba(255,255,255,0.4);align-self:center;transition:opacity 0.3s;">Saved</span>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" style="background:none;border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.35);padding:0.35rem 1.2rem;border-radius:4px;font-size:0.75rem;cursor:pointer;letter-spacing:1px;transition:color 0.2s,border-color 0.2s;"
                onmouseover="this.style.color='rgba(255,255,255,0.7)';this.style.borderColor='rgba(255,255,255,0.3)'"
                onmouseout="this.style.color='rgba(255,255,255,0.35)';this.style.borderColor='rgba(255,255,255,0.15)'">Log out</button>
            </form>
          </div>
    </div>
  </div>
  <div id="loading" class="hidden"><span class="spinner"></span><span id="loading-text">Loading…</span></div>
</div>
<div id="objListWrap" style="display:none;position:fixed;top:3rem;right:2.2rem;z-index:25;max-height:60vh;overflow-y:auto;background:rgba(10,10,20,0.6);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,0.08);border-radius:6px;padding:0.4rem 0;min-width:160px;">
  <div style="padding:0.2rem 0.6rem 0.4rem;font-size:0.6rem;color:rgba(255,255,255,0.25);letter-spacing:1px;text-transform:uppercase;border-bottom:1px solid rgba(255,255,255,0.05);">Objects</div>
  <div id="objList"></div>
</div>
<div id="objToggle" style="display:none;position:fixed;top:3rem;right:0.5rem;z-index:26;background:rgba(10,10,20,0.6);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,0.08);border-radius:4px;width:1.4rem;height:1.4rem;align-items:center;justify-content:center;cursor:pointer;color:rgba(255,255,255,0.3);font-size:0.7rem;transition:color 0.2s,background 0.2s;">◀</div>
    <div id="sizeHint" style="position:fixed;top:24%;left:50%;transform:translate(-50%,-50%);z-index:40;background:rgba(10,10,25,0.92);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.12);border-radius:12px;padding:1.5rem 2rem;text-align:center;pointer-events:none;user-select:none;max-width:320px;color:rgba(255,255,255,0.8);font-size:1rem;font-weight:400;letter-spacing:0.3px;line-height:1.6;">
        <strong style="color:#ffd166;">Warning:</strong><br>
        After uploading or dragging your files, use
        <kbd style="display:inline-block;padding:0.1rem 0.5rem;border:1px solid rgba(255,255,255,0.2);border-radius:3px;font-family:inherit;font-size:0.75rem;color:#88aaff;">+</kbd>
        and
        <kbd style="display:inline-block;padding:0.1rem 0.5rem;border:1px solid rgba(255,255,255,0.2);border-radius:3px;font-family:inherit;font-size:0.75rem;color:#88aaff;">-</kbd>
        to adjust the object size for your comfort.
        To resize object<br>then press <kbd style="display:inline-block;padding:0.1rem 0.5rem;border:1px solid rgba(255,255,255,0.2);border-radius:3px;font-family:inherit;font-size:0.75rem;color:#88aaff;">L</kbd> to finish
    </div>
<div id="stats"></div>
<div id="crosshair">+</div>
<div id="hud-keys"></div>
<input type="file" id="placeFileInput" accept=".obj,.mtl,.png" multiple hidden>
