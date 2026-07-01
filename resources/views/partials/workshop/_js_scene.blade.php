const clock = new THREE.Clock();

// Scene
const scene = new THREE.Scene();
const sceneKeep = new Set();
scene.background = new THREE.Color(0x111520);
scene.fog = new THREE.Fog(0x111520, 15, 30);

// Camera
const camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 0.05, 50);
camera.position.set(0, 1.6, 0);

// Renderer
const renderer = new THREE.WebGLRenderer({ antialias: true, powerPreference: 'high-performance' });
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
renderer.shadowMap.enabled = true;
renderer.shadowMap.type = THREE.PCFSoftShadowMap;
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.2;
renderer.outputColorSpace = THREE.SRGBColorSpace;
renderer.sortObjects = true;
document.body.prepend(renderer.domElement);

// Pre-allocated vectors for animation loop (reduce GC)
const _movForward = new THREE.Vector3();
const _movRight = new THREE.Vector3();

// Controls
const controls = new PointerLockControls(camera, renderer.domElement);

let modelLoaded = false;
let modelGroup = null;
let _hintShown = false;

// Movement state
const keys = { forward: false, backward: false, left: false, right: false, up: false, down: false, sprint: false };
const velocity = new THREE.Vector3();
const direction = new THREE.Vector3();
const moveSpeed = 10.0;
const sprintMultiplier = 5.5;

const keyAliases = {
  Backspace: 'Delete',
};
// Input
document.addEventListener('keydown', (e) => {
  const code = keyAliases[e.code] || e.code;
  const mov = keyMovementMap[code];
  if (mov) { keys[mov] = true; e.preventDefault(); return; }
  const handler = keyActionMap[code];
  if (handler) { handler(e); return; }
  const condHandler = keyConditionalMap[code];
  if (condHandler) { condHandler(e); return; }
});
document.addEventListener('keyup', (e) => {
  const mov = keyMovementMap[e.code];
  if (mov) { keys[mov] = false; }
});

// ── Plan / Limit system ──
function fetchLimits() {
  if (USER_ROLE !== 'user') { userLimits = { max_sessions: -1, max_objects_per_scene: -1, is_expired: false }; return; }
  fetch(LIMITS_URL, { headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } })
    .then(r => r.json())
    .then(data => {
      userLimits = data;
      if (data.is_expired && !data.has_pending_request && !sessionStorage.getItem('renew_dismissed')) {
        showRenewModal();
      }
    })
    .catch(() => {});
}

function showRenewModal() {
  if (controls.isLocked) controls.unlock();
  document.getElementById('renewModal').style.display = 'flex';
}

function submitRenewal() {
  if (!userLimits || !userLimits.plan_id) return;
  const btn = document.getElementById('renewConfirm');
  btn.disabled = true;
  btn.textContent = 'Submitting…';
  fetch(UPGRADE_REQUEST_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
    body: JSON.stringify({ plan_id: userLimits.plan_id, name: USER_NAME, email: USER_EMAIL, phone: '' }),
  }).then(r => r.json().then(data => ({ status: r.status, data })))
    .then(({ status, data }) => {
      document.getElementById('renewModal').style.display = 'none';
      if (status === 201 || status === 409) {
        alert('Renewal request submitted! An admin will review it.');
        fetchLimits();
      } else {
        btn.disabled = false;
        btn.textContent = 'Renew for Another Month';
        alert(data.message || 'Something went wrong.');
      }
    }).catch(() => {
      btn.disabled = false;
      btn.textContent = 'Renew for Another Month';
      alert('Network error. Please try again.');
    });
}

document.getElementById('renewConfirm').addEventListener('click', submitRenewal);
document.getElementById('renewDismiss').addEventListener('click', () => {
  document.getElementById('renewModal').style.display = 'none';
  sessionStorage.setItem('renew_dismissed', '1');
  location.reload();
});

function fetchPlans() {
  fetch(PLANS_URL, { headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } })
    .then(r => r.json())
    .then(data => { plansList = data; })
    .catch(() => {});
}

function fetchSocialDiscounts() {
  fetch(SOCIAL_DISCOUNTS_URL, { headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } })
    .then(r => r.json())
    .then(data => {
      socialDiscountsList = data.discounts || data;
      userPostedIds = data.posted_ids || [];
    })
    .catch(() => {});
}

function showUpgradeModal(type) {
  upgradeModalType = type;
  if (controls.isLocked) controls.unlock();
  const modal = document.getElementById('upgradeModal');
  const icon = document.getElementById('upgradeIcon');
  const title = document.getElementById('upgradeTitle');
  const desc = document.getElementById('upgradeDesc');
  const msg = document.getElementById('upgradeLimitMsg');
  const list = document.getElementById('upgradePlansList');

  if (userLimits) {
    icon.textContent = userLimits.plan_icon || '🆓';
    if (userLimits.is_expired) {
      title.textContent = userLimits.plan_name + ' Plan Expired';
      desc.textContent = 'Your ' + userLimits.plan_name + ' plan has expired. Renew to continue using its benefits.';
      msg.textContent = 'Renew your plan to add more ' + (type === 'session' ? 'sessions' : 'objects') + '.';
    } else {
      title.textContent = userLimits.plan_name + ' Plan';
      if (type === 'session') {
        desc.textContent = 'Your current plan allows ' + (userLimits.max_sessions === -1 ? 'unlimited' : userLimits.max_sessions) + ' sessions.';
        msg.textContent = 'Session limit reached (' + userLimits.current_sessions + '/' + userLimits.max_sessions + '). Upgrade to create more.';
      } else {
        desc.textContent = 'Your current plan allows ' + (userLimits.max_objects_per_scene === -1 ? 'unlimited' : userLimits.max_objects_per_scene) + ' objects per scene.';
        msg.textContent = 'Object limit reached. Upgrade to place more objects.';
      }
    }
  }

  const discEl = document.getElementById('upgradeDiscounts');
  if (discEl) {
    if (socialDiscountsList.length) {
      discEl.style.display = 'block';
      const shareUrl = function(raw) {
        if (!raw) return null;
        return raw.replace('@{{URL}}', encodeURIComponent(window.location.origin));
      };
      discEl.innerHTML = '<div style="font-size:0.75rem;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.75rem;">Get a discount — share & earn</div>' +
        socialDiscountsList.map(d => {
          const posted = userPostedIds.indexOf(d.id) !== -1;
          const url = shareUrl(d.share_url);
          return '<div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;border-bottom:1px solid rgba(255,255,255,0.04);">' +
            '<span style="font-size:1.2rem;width:1.5rem;text-align:center;">' + (d.icon || '📱') + '</span>' +
            '<div style="flex:1;font-size:0.8rem;color:rgba(255,255,255,0.7);">' + d.description +
            (url ? '<br><a href="' + url + '" target="_blank" style="color:#88aaff;font-size:0.75rem;text-decoration:none;">Share on ' + d.label + ' →</a>' : '') +
            '</div>' +
            (posted
              ? '<span style="font-size:0.75rem;font-weight:600;color:#34d399;white-space:nowrap;">✓ Posted</span>'
              : '<span style="font-size:0.75rem;font-weight:600;color:#34d399;white-space:nowrap;">' + d.discount_percent + '% off</span>'
            ) +
            (posted ? '' : '<button class="claim-post-btn" data-discount-id="' + d.id + '" data-label="' + d.label + '" style="background:rgba(100,140,255,0.15);border:1px solid rgba(100,140,255,0.25);color:#88aaff;padding:0.25rem 0.6rem;border-radius:4px;font-size:0.7rem;cursor:pointer;white-space:nowrap;">I Posted</button>') +
          '</div>';
        }).join('');
      discEl.querySelectorAll('.claim-post-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
          showClaimPostForm(this.dataset.discountId, this.dataset.label);
        });
      });
    } else {
      discEl.style.display = 'none';
    }
  }

  list.innerHTML = '';
  if (userLimits && userLimits.has_pending_request) {
    const el = document.createElement('div');
    el.style.cssText = 'padding:1rem;background:rgba(255,200,50,0.08);border:1px solid rgba(255,200,50,0.15);border-radius:8px;text-align:center;';
    el.innerHTML = '<div style="font-size:1.5rem;margin-bottom:0.5rem;">⏳</div><p style="color:#fbbf24;font-size:0.85rem;font-weight:500;">Your upgrade request is pending approval.</p><p style="color:rgba(255,255,255,0.35);font-size:0.75rem;margin-top:0.25rem;">An admin will review it shortly.</p>';
    list.appendChild(el);
  } else if (plansList.length) {
    const userDiscount = socialDiscountsList.reduce(function(sum, d) {
      return userPostedIds.indexOf(d.id) !== -1 ? sum + (d.discount_percent || 0) : sum;
    }, 0);
    const hasDiscount = userDiscount > 0;
    plansList.forEach(p => {
      if (p.slug === 'free') return;
      const price = parseFloat(p.price);
      const discounted = price * (1 - userDiscount / 100);
      const btn = document.createElement('button');
      btn.style.cssText = 'display:flex;align-items:center;gap:0.75rem;width:100%;padding:0.75rem 1rem;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:8px;color:#e2e8f0;font-size:0.85rem;cursor:pointer;transition:all 0.2s;text-align:left;';
      btn.innerHTML = '<span style="font-size:1.4rem;">' + p.icon + '</span><div style="flex:1;"><div style="font-weight:500;">' + p.name + '</div><div style="font-size:0.75rem;color:rgba(255,255,255,0.4);">' + (p.max_sessions === -1 ? 'Unlimited' : p.max_sessions) + ' sessions &middot; ' + (p.max_objects_per_scene === -1 ? 'Unlimited' : p.max_objects_per_scene) + ' objects' + (p.duration_days ? ' &middot; ' + p.duration_days + ' days' : '') + '</div></div><div style="text-align:right;">' +
        (hasDiscount ? '<span style="font-size:0.7rem;color:rgba(255,255,255,0.3);text-decoration:line-through;">$' + price.toFixed(2) + '</span><br><span style="font-size:1rem;font-weight:600;color:#34d399;">$' + discounted.toFixed(2) + '</span>' : '<span style="font-size:1rem;font-weight:600;color:#88aaff;">$' + price.toFixed(2) + '</span>') +
      '</div>';
      btn.addEventListener('mouseenter', () => { btn.style.borderColor = 'rgba(100,140,255,0.4)'; btn.style.background = 'rgba(100,140,255,0.08)'; });
      btn.addEventListener('mouseleave', () => { btn.style.borderColor = 'rgba(255,255,255,0.08)'; btn.style.background = 'rgba(255,255,255,0.03)'; });
      btn.addEventListener('click', () => showRequestForm(p.id, p.name, p.icon, hasDiscount ? discounted : price));
      list.appendChild(btn);
    });
  } else {
    const el = document.createElement('p');
    el.style.cssText = 'color:rgba(255,255,255,0.3);font-size:0.8rem;';
    el.textContent = 'Loading plans…';
    list.appendChild(el);
  }

  modal.style.display = 'flex';
}

function showRequestForm(planId, planName, planIcon, planPrice) {
  document.getElementById('upgradeModal').style.display = 'none';
  if (controls.isLocked) controls.unlock();
  const modal = document.getElementById('requestModal');
  const userDiscount = socialDiscountsList.reduce(function(sum, d) {
    return userPostedIds.indexOf(d.id) !== -1 ? sum + (d.discount_percent || 0) : sum;
  }, 0);
  const price = parseFloat(planPrice);
  const labelHtml = userDiscount > 0
    ? 'You selected: <strong style="color:#fff;">' + planIcon + ' ' + planName + '</strong><br><span style="color:#34d399;font-size:1.1rem;font-weight:600;">$' + price.toFixed(2) + '</span> <span style="color:rgba(255,255,255,0.25);font-size:0.8rem;text-decoration:line-through;">$' + (price / (1 - userDiscount / 100)).toFixed(2) + '</span> <span class="text-emerald-400" style="font-size:0.7rem;color:#34d399;">(' + userDiscount + '% off)</span>'
    : 'You selected: <strong style="color:#fff;">' + planIcon + ' ' + planName + ' ($' + price.toFixed(2) + ')</strong>';
  document.getElementById('requestPlanLabel').innerHTML = labelHtml;
  document.getElementById('reqError').style.display = 'none';
  document.getElementById('reqError').textContent = '';
  modal.style.display = 'flex';

  const submitBtn = document.getElementById('reqSubmit');
  const newBtn = submitBtn.cloneNode(true);
  submitBtn.parentNode.replaceChild(newBtn, submitBtn);
  newBtn.addEventListener('click', () => {
    const name = document.getElementById('reqName').value.trim();
    const email = document.getElementById('reqEmail').value.trim();
    const phone = document.getElementById('reqPhone').value.trim();
    if (!name || !email || !phone) {
      const err = document.getElementById('reqError');
      err.textContent = 'All fields are required.';
      err.style.display = 'block';
      return;
    }
    newBtn.disabled = true;
    newBtn.textContent = 'Submitting…';
    fetch(UPGRADE_REQUEST_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
      body: JSON.stringify({ plan_id: planId, name, email, phone }),
    }).then(r => r.json().then(data => ({ status: r.status, data })))
      .then(({ status, data }) => {
        if (status === 201) {
          document.getElementById('requestModal').style.display = 'none';
          alert('Request submitted! An admin will review your upgrade request.');
        } else {
          const err = document.getElementById('reqError');
          err.textContent = data.message || 'Failed to submit request.';
          err.style.display = 'block';
          newBtn.disabled = false;
          newBtn.textContent = 'Submit Request';
        }
      }).catch(() => {
        const err = document.getElementById('reqError');
        err.textContent = 'Network error. Please try again.';
        err.style.display = 'block';
        newBtn.disabled = false;
        newBtn.textContent = 'Submit Request';
      });
  });
}

function showClaimPostForm(discountId, label) {
  document.getElementById('upgradeModal').style.display = 'none';
  if (controls.isLocked) controls.unlock();
  const modal = document.getElementById('claimPostModal');
  document.getElementById('claimPostLabel').textContent = label;
  document.getElementById('claimPostError').style.display = 'none';
  document.getElementById('claimPostError').textContent = '';
  document.getElementById('claimPostUrl').value = '';
  modal.style.display = 'flex';

  const submitBtn = document.getElementById('claimPostSubmit');
  const newBtn = submitBtn.cloneNode(true);
  submitBtn.parentNode.replaceChild(newBtn, submitBtn);
  newBtn.addEventListener('click', function() {
    const url = document.getElementById('claimPostUrl').value.trim();
    if (!url) {
      document.getElementById('claimPostError').textContent = 'Please paste your post URL.';
      document.getElementById('claimPostError').style.display = 'block';
      return;
    }
    newBtn.disabled = true;
    newBtn.textContent = 'Submitting…';
    fetch(SOCIAL_POSTS_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
      body: JSON.stringify({ social_discount_id: discountId, post_url: url }),
    }).then(r => r.json().then(data => ({ status: r.status, data })))
      .then(function(res) {
        if (res.status === 201 || res.status === 409) {
          document.getElementById('claimPostModal').style.display = 'none';
          alert(res.data.message || 'Post submitted! An admin will verify it.');
          fetchSocialDiscounts();
        } else {
          document.getElementById('claimPostError').textContent = res.data.message || 'Failed to submit.';
          document.getElementById('claimPostError').style.display = 'block';
          newBtn.disabled = false;
          newBtn.textContent = 'Submit';
        }
      }).catch(function() {
        document.getElementById('claimPostError').textContent = 'Network error. Please try again.';
        document.getElementById('claimPostError').style.display = 'block';
        newBtn.disabled = false;
        newBtn.textContent = 'Submit';
      });
  });
}

document.getElementById('upgradeClose').addEventListener('click', () => {
  document.getElementById('upgradeModal').style.display = 'none';
  if (modelLoaded && !controls.isLocked) controls.lock();
});
document.getElementById('reqCancel').addEventListener('click', () => {
  document.getElementById('requestModal').style.display = 'none';
  if (modelLoaded && !controls.isLocked) controls.lock();
});
document.getElementById('claimPostCancel').addEventListener('click', () => {
  document.getElementById('claimPostModal').style.display = 'none';
  if (modelLoaded && !controls.isLocked) controls.lock();
});

fetchLimits();
fetchPlans();
fetchSocialDiscounts();

// Upload files to server
function uploadFilesToServer(files, modelName, sessionId) {
  const formData = new FormData();
  for (const file of files) formData.append('files[]', file);
  if (modelName) formData.append('model_name', modelName);
  if (sessionId) formData.append('session_id', sessionId);
  formData.append('_token', '{{ csrf_token() }}');
  return fetch(UPLOAD_URL, {
    method: 'POST',
    body: formData,
  }).then(r => {
    if (r.ok) { fetchSessions(); fetchLimits(); return r.json().then(d => { if (!currentSessionId) currentSessionId = d.session_id; return d; }); }
    r.text().then(t => console.warn('Upload failed:', t));
  }).catch(err => console.warn('Upload error:', err));
}

// File upload
function loadModelFiles(objFile, mtlFile, pngFile) {
  showSplash();
  splashProgress('Loading model\u2026');
  const objLoader = new OBJLoader();
  var _parseDone = false;
  var _uploadDone = false;

  function finishLoad() {
    if (!_parseDone || !_uploadDone) return;
    hideSizeHint();
    dropzone.style.display = 'none';
    instructions.classList.add('show');
    saveBtn.classList.add('show');
    hudSaveBtn.style.display = 'inline';
    setStats('Model loaded');
    splashReady(() => {
      if (modelGroup && !selectedObject) {
        selectObject(modelGroup);
      }
    });
  }

  function parseObj(text) {
    const obj = objLoader.parse(text);
    let idx = 0;
    obj.traverse((child) => {
      if (child.isMesh) {
    child.castShadow = true;
    child.receiveShadow = true;
    child.frustumCulled = true;
    child.matrixAutoUpdate = true;
        const meshColor = defaultManip.random_color ? randomColor(idx) : new THREE.Color(defaultManip.color);
        child.material = new THREE.MeshStandardMaterial({
          color: meshColor,
          roughness: defaultManip.roughness,
          metalness: defaultManip.metalness,
        });
        idx++;
      }
    });
    scene.add(obj);
    modelGroup = obj;
    modelLoaded = true;
    updateObjectList();
    if (!_hintShown) {
      _hintShown = true;
      showSizeHint();
    }
    _parseDone = true;
    finishLoad();
  }

  if (mtlFile) {
    const mtlReader = new FileReader();
    mtlReader.onload = (e) => {
      let mtlText = e.target.result;
      mtlText = mtlText.replace(/map_\w+\s+\S*\.tga\b/gi, '');
      const mtlLoader = new MTLLoader();
      const materials = mtlLoader.parse(mtlText);
      objLoader.setMaterials(materials);
      const objReader = new FileReader();
      objReader.onload = (e2) => { parseObj(e2.target.result); };
      objReader.readAsText(objFile);
    };
    mtlReader.readAsText(mtlFile);
  } else {
    const reader = new FileReader();
    reader.onload = (e) => { parseObj(e.target.result); };
    reader.readAsText(objFile);
  }

  if (!currentSessionId && userLimits && userLimits.max_sessions !== -1 && userLimits.current_sessions >= userLimits.max_sessions) {
    showUpgradeModal('session');
    _uploadDone = true; finishLoad();
    return;
  }
  const uploadFiles = [objFile];
  if (mtlFile) uploadFiles.push(mtlFile);
  if (pngFile) uploadFiles.push(pngFile);
  uploadFilesToServer(uploadFiles, objFile.name.replace(/\.obj$/i, '')).then(d => {
    if (d && d.records) {
      const pngRecord = d.records.find(r => r.original_name.toLowerCase().endsWith('.png'));
      if (pngRecord && modelGroup) {
        mainModelTextureFilePath = pngRecord.file_path;
        const url = '/storage/' + pngRecord.file_path;
        splashProgress('Loading textures\u2026');
        if (!textureCache[pngRecord.file_path]) {
          textureCache[pngRecord.file_path] = new THREE.TextureLoader().load(url, handleTextureLoad);
        }
        const tex = textureCache[pngRecord.file_path];
        modelGroup.traverse(c => {
          if (c.isMesh && c.material) {
            const mats = Array.isArray(c.material) ? c.material : [c.material];
            mats.forEach(m => applyTextureToMaterial(m, tex));
          }
        });
      }
    }
    _uploadDone = true;
    finishLoad();
  }).catch(function() { _uploadDone = true; finishLoad(); });
}

// Transform gizmo
const transformControls = new TransformControls(camera, renderer.domElement);
scene.add(transformControls);
sceneKeep.add(transformControls);

// Place object
const placeFileInput = document.getElementById('placeFileInput');
let placePos = null;
let placing = false;
let selectedObject = null;
let prevEmissive = null;
let textureTarget = null;

function clearSelection() {
  if (selectedObject) {
    transformControls.detach();
    transformControls.enabled = true;
    selectedObject.traverse((c) => {
      if (c.isMesh && c.material && c.material.emissive) c.material.emissive.setHex(prevEmissive || 0);
    });
    blocker.style.display = '';
  }
  selectedObject = null;
  prevEmissive = null;
  setStats(modelLoaded ? 'Model loaded' : '');
}

function selectObject(obj) {
  clearHoverHighlight();
  clearSelection();
  selectedObject = obj;
  obj.traverse((c) => {
    if (c.isMesh && c.material && c.material.emissive) {
      prevEmissive = c.material.emissive.getHex();
      c.material.emissive.setHex(0x4488ff);
      c.material.emissiveIntensity = 0.3;
    }
  });
  transformControls.attach(obj);
  controls.unlock();
  blocker.style.display = 'none';
  setStats('Drag gizmo — G/R/S for mode | L to finish');
}

function isGizmoChild(obj) {
  let p = obj.parent;
  while (p) { if (p === transformControls) return true; p = p.parent; }
  return false;
}

function isMainModelChild(obj) {
  let p = obj.parent;
  while (p) { if (p === modelGroup) return true; p = p.parent; }
  return false;
}

let hoveredObjectId = null;
let hoverOriginalEmissives = [];

function findMeshByKey(key) {
  if (!modelGroup) return null;
  let result = null;
  modelGroup.traverse(c => { if (c.isMesh && getMeshKey(c) === key) result = c; });
  return result;
}

function applyHoverHighlight(id) {
  if (id.startsWith('mm_')) {
    const key = id.slice(3);
    const mesh = findMeshByKey(key);
    if (!mesh) return;
    const mats = Array.isArray(mesh.material) ? mesh.material : [mesh.material];
    mats.forEach(m => {
      if (m.emissive) {
        hoverOriginalEmissives.push({ material: m, color: m.emissive.getHex(), intensity: m.emissiveIntensity });
        m.emissive.setHex(0x4488ff);
        m.emissiveIntensity = 0.3;
      }
    });
  } else if (id.startsWith('po_')) {
    const idx = parseInt(id.slice(3), 10);
    const po = placedObjects[idx];
    if (!po || !po.group) return;
    po.group.traverse(c => {
      if (!c.isMesh) return;
      const mats = Array.isArray(c.material) ? c.material : [c.material];
      mats.forEach(m => {
        if (m.emissive) {
          hoverOriginalEmissives.push({ material: m, color: m.emissive.getHex(), intensity: m.emissiveIntensity });
          m.emissive.setHex(0x4488ff);
          m.emissiveIntensity = 0.3;
        }
      });
    });
  }
}

function clearHoverHighlight() {
  hoverOriginalEmissives.forEach(({ material, color, intensity }) => {
    material.emissive.setHex(color);
    material.emissiveIntensity = intensity;
  });
  hoverOriginalEmissives = [];
  hoveredObjectId = null;
}

function updateObjectList() {
  clearHoverHighlight();
  const wrap = document.getElementById('objListWrap');
  const list = document.getElementById('objList');
  const toggle = document.getElementById('objToggle');
  if (!wrap || !list || !toggle) return;
  objListEntries = [];
  if (modelGroup) {
    modelGroup.traverse(c => {
      if (!c.isMesh) return;
      const key = getMeshKey(c);
      objListEntries.push({ id: 'mm_' + key, label: key, visible: c.visible, toggle: () => { c.visible = !c.visible; updateObjectList(); markDirty(); } });
    });
  }
  placedObjects.forEach((obj, i) => {
    objListEntries.push({ id: 'po_' + i, label: 'Object ' + (i + 1), visible: obj.group.visible, toggle: () => { obj.group.visible = !obj.group.visible; updateObjectList(); markDirty(); } });
  });
  if (objListEntries.length) {
    wrap.style.display = '';
    toggle.style.display = 'flex';
    list.innerHTML = objListEntries.map(e => `<div style="display:flex;align-items:center;gap:0.4rem;padding:0.25rem 0.6rem;font-size:0.7rem;color:rgba(255,255,255,0.5);cursor:default;" data-obj-id="${e.id}"><span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${e.label}</span><span class="obj-eye" style="cursor:pointer;font-size:0.8rem;opacity:0.5;transition:opacity 0.2s;">${e.visible ? '👁' : '🚫'}</span></div>`).join('');
  } else {
    wrap.style.display = 'none';
    toggle.style.display = 'none';
    list.innerHTML = '';
  }
}

document.getElementById('objListWrap').addEventListener('click', (e) => {
  const eye = e.target.closest('.obj-eye');
  if (!eye) return;
  e.stopPropagation();
  const row = eye.closest('[data-obj-id]');
  if (!row) return;
  const id = row.dataset.objId;
  const entry = objListEntries.find(ee => ee.id === id);
  if (entry) entry.toggle();
});
document.getElementById('objToggle').addEventListener('click', (e) => {
  const wrap = document.getElementById('objListWrap');
  if (!wrap) return;
  const hidden = wrap.style.display === 'none';
  wrap.style.display = hidden ? '' : 'none';
  e.currentTarget.textContent = hidden ? '◀' : '▶';
  e.stopPropagation();
});

document.getElementById('objListWrap').addEventListener('mouseover', (e) => {
  const row = e.target.closest('[data-obj-id]');
  if (!row) return;
  const id = row.dataset.objId;
  if (hoveredObjectId === id) return;
  clearHoverHighlight();
  hoveredObjectId = id;
  applyHoverHighlight(id);
});
document.getElementById('objListWrap').addEventListener('mouseout', (e) => {
  const row = e.target.closest('[data-obj-id]');
  if (!row) return;
  if (e.relatedTarget && row.contains(e.relatedTarget)) return;
  clearHoverHighlight();
});

function selectHovered(e) {
  const _h = document.getElementById('sizeHint');
  if (_h) _h.style.display = 'none';
  if (!modelLoaded) return;
  if (selectedObject) { clearSelection(); controls.lock(); return; }
  if (e && e.shiftKey) {
    const mesh = getHoveredMesh();
    if (!mesh) return;
    let parent = mesh;
    while (parent.parent && parent.parent !== modelGroup) parent = parent.parent;
    selectObject(parent);
    return;
  }
  const dir = new THREE.Vector3(0, 0, -1).applyQuaternion(camera.quaternion);
  const start = camera.position.clone();
  raycaster.set(start, dir);
  const all = [];
  scene.traverse((c) => { if (c.isMesh && !isGizmoChild(c) && !isMainModelChild(c)) all.push(c); });
  const hits = raycaster.intersectObjects(all);
  if (hits.length === 0) return;
  const mesh = hits[0].object;
  let parent = mesh;
  while (parent.parent && parent.parent !== scene) parent = parent.parent;
  selectObject(parent);
}

function placeObjectClick() {
  if (!modelLoaded || placing) return;
  if (userLimits && userLimits.is_expired) {
    showUpgradeModal('object');
    return;
  }
  if (userLimits && userLimits.max_objects_per_scene !== -1 && placedObjects.length >= userLimits.max_objects_per_scene) {
    showUpgradeModal('object');
    return;
  }
  placing = true;
  const dir = new THREE.Vector3(0, 0, -1).applyQuaternion(camera.quaternion);
  const start = camera.position.clone();
  raycaster.set(start, dir);
  const meshes = [];
  if (modelGroup) modelGroup.traverse((c) => { if (c.isMesh) meshes.push(c); });
  const hits = raycaster.intersectObjects(meshes);
  placePos = hits.length > 0
    ? hits[0].point.clone()
    : start.clone().add(dir.multiplyScalar(2));
  placeFileInput.value = '';
  placeFileInput.click();
}

placeFileInput.addEventListener('change', () => {
  placing = false;
  if (!placeFileInput.files.length) return;
  let objFile, mtlFile, pngFile;
  for (const file of placeFileInput.files) {
    if (file.name.endsWith('.obj')) objFile = file;
    else if (file.name.endsWith('.mtl')) mtlFile = file;
    else if (file.name.endsWith('.png')) pngFile = file;
  }
  if (!objFile) return;

  const objLoader = new OBJLoader();
  function placeObj(text) {
    const obj = objLoader.parse(text);
    let idx = 0;
    obj.traverse((child) => {
      if (child.isMesh) {
    child.castShadow = true;
    child.receiveShadow = true;
    child.frustumCulled = true;
    child.matrixAutoUpdate = true;
        const meshColor = defaultManip.random_color ? randomColor(idx) : new THREE.Color(defaultManip.color);
        child.material = new THREE.MeshStandardMaterial({
          color: meshColor,
          roughness: defaultManip.roughness,
          metalness: defaultManip.metalness,
        });
        idx++;
      }
    });
    obj.position.copy(placePos || camera.position);
    scene.add(obj);
    obj.scale.set(0.01, 0.01, 0.01);
    setTimeout(() => {
      selectObject(obj);
    }, 100);

    const upFiles = [objFile];
    if (mtlFile) upFiles.push(mtlFile);
    if (pngFile) upFiles.push(pngFile);
    uploadFilesToServer(upFiles, objFile.name.replace(/\.obj$/i, ''), currentSessionId).then(d => {
      if (d && d.records) {
        placedObjects.push({ group: obj, file_ids: d.records.map(r => r.id) });
        updateObjectList();
        const pngRecord = d.records.find(r => r.original_name.toLowerCase().endsWith('.png'));
        if (pngRecord) {
          placedObjects[placedObjects.length - 1].uploadedTexturePath = pngRecord.file_path;
          const url = '/storage/' + pngRecord.file_path;
          if (!textureCache[pngRecord.file_path]) {
            textureCache[pngRecord.file_path] = new THREE.TextureLoader().load(url, handleTextureLoad);
          }
          const tex = textureCache[pngRecord.file_path];
          obj.traverse(c => {
            if (c.isMesh && c.material) {
              const mats = Array.isArray(c.material) ? c.material : [c.material];
              mats.forEach(m => applyTextureToMaterial(m, tex));
            }
          });
        }
        markDirty();
      }
    });
  }

  if (mtlFile) {
    const mtlReader = new FileReader();
    mtlReader.onload = (e) => {
      let mtlText = e.target.result;
      mtlText = mtlText.replace(/map_\w+\s+\S*\.tga\b/gi, '');
      const mtlLoader = new MTLLoader();
      const materials = mtlLoader.parse(mtlText);
      objLoader.setMaterials(materials);
      const objReader = new FileReader();
      objReader.onload = (e2) => placeObj(e2.target.result);
      objReader.readAsText(objFile);
    };
    mtlReader.readAsText(mtlFile);
  } else {
    const reader = new FileReader();
    reader.onload = (e) => placeObj(e.target.result);
    reader.readAsText(objFile);
  }
});

function handleFiles(files) {
  let objFile, mtlFile, pngFile;
  for (const file of files) {
    if (file.name.endsWith('.obj')) objFile = file;
    else if (file.name.endsWith('.mtl')) mtlFile = file;
    else if (file.name.endsWith('.png')) pngFile = file;
  }
  if (!objFile) return;
  if (userLimits && userLimits.is_expired) {
    showUpgradeModal('session');
    return;
  }
  if (!currentSessionId && userLimits && userLimits.max_sessions !== -1 && userLimits.current_sessions >= userLimits.max_sessions) {
    showUpgradeModal('session');
    return;
  }
  loadModelFiles(objFile, mtlFile, pngFile);
}

fileInput.addEventListener('change', () => {
  if (fileInput.files.length) handleFiles(fileInput.files);
});

dropzone.addEventListener('click', () => {
  if (userLimits && userLimits.is_expired) {
    showUpgradeModal('session');
    return;
  }
  if (!currentSessionId && userLimits && userLimits.max_sessions !== -1 && userLimits.current_sessions >= userLimits.max_sessions) {
    showUpgradeModal('session');
    return;
  }
  fileInput.click();
});
dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
dropzone.addEventListener('drop', (e) => {
  e.preventDefault();
  dropzone.classList.remove('dragover');
  handleFiles(e.dataTransfer.files);
});

// Pointer lock
blocker.addEventListener('click', (e) => {
  if (!modelLoaded || selectedObject) return;
  controls.lock();
});
controls.addEventListener('lock', () => {
  blocker.classList.add('active');
});
controls.addEventListener('unlock', () => {
  blocker.classList.remove('active');
});

// Lighting
const ambientLight = new THREE.AmbientLight(0x404060, 0.6);
scene.add(ambientLight);
sceneKeep.add(ambientLight);

const hemisphereLight = new THREE.HemisphereLight(0x87ceeb, 0x3a2a1a, 0.4);
scene.add(hemisphereLight);
sceneKeep.add(hemisphereLight);

const dirLight = new THREE.DirectionalLight(0xffeedd, 1.8);
dirLight.position.set(-0.5, 25, -0.7);
dirLight.castShadow = true;
dirLight.shadow.mapSize.width = 512;
dirLight.shadow.mapSize.height = 512;
dirLight.shadow.camera.near = 0.5;
dirLight.shadow.camera.far = 25;
dirLight.shadow.camera.left = -10;
dirLight.shadow.camera.right = 10;
dirLight.shadow.camera.top = 10;
dirLight.shadow.camera.bottom = -10;
scene.add(dirLight);
sceneKeep.add(dirLight);

const fillLight = new THREE.DirectionalLight(0x8888ff, 0.4);
fillLight.position.set(-3, 1, -3);
scene.add(fillLight);
sceneKeep.add(fillLight);

const rimLight = new THREE.DirectionalLight(0xffaa66, 0.3);
rimLight.position.set(-2, 3, 5);
scene.add(rimLight);
sceneKeep.add(rimLight);

// Ground plane
const groundGeo = new THREE.PlaneGeometry(40, 40);
const groundMat = new THREE.ShadowMaterial({ opacity: 0.3 });
const ground = new THREE.Mesh(groundGeo, groundMat);
ground.rotation.x = -Math.PI / 2;
ground.position.y = -1.8;
ground.receiveShadow = true;
scene.add(ground);
sceneKeep.add(ground);

// Helper grid (subtle)
const gridHelper = new THREE.GridHelper(30, 20, 0x444466, 0x333355);
gridHelper.position.y = -1.79;
gridHelper.material.transparent = true;
gridHelper.material.opacity = 0.15;
scene.add(gridHelper);
sceneKeep.add(gridHelper);

// Small marker sphere at origin
const markerMat = new THREE.MeshBasicMaterial({ color: 0x88aaff, transparent: true, opacity: 0.2 });
const marker = new THREE.Mesh(new THREE.SphereGeometry(0.05, 8, 8), markerMat);
marker.position.set(0, -1.7, 0);
scene.add(marker);
sceneKeep.add(marker);

const raycaster = new THREE.Raycaster();
const actionHistory = [];
