function logObjectSize() {
  console.log('[Inspect] called');
  const target = selectedObject || getHoveredMesh() || getHoveredPlacedMesh();
  console.log('[Inspect] target:', target ? (target.type || 'unknown') : null);
  if (!target) { setStats('Nothing to inspect'); return; }
  try {
    const box = new THREE.Box3().setFromObject(target);
    const size = box.getSize(new THREE.Vector3());
    if (!size || (size.x === 0 && size.y === 0 && size.z === 0)) {
      setStats('Object has no size'); console.warn('[Inspect] Empty bounding box'); return;
    }
    const s = target.scale || { x: 1, y: 1, z: 1 };
    const w = (size.x * s.x).toFixed(3);
    const h = (size.y * s.y).toFixed(3);
    const d = (size.z * s.z).toFixed(3);
    let texInfo = '';
    target.traverse(c => {
      if (!c.isMesh || !c.material) return;
      const mats = Array.isArray(c.material) ? c.material : [c.material];
      for (const m of mats) {
        if (m.map && m.map.repeat) {
          texInfo = ` | Repeat: ${m.map.repeat.x.toFixed(2)}`;
          return;
        }
      }
    });
    const msg = `Size: ${w} × ${h} × ${d}${texInfo}`;
    console.log(`[Inspect] ${msg}`);
    setStats(msg);
  } catch (err) {
    console.error('[Inspect] Error:', err);
    setStats('Inspect error');
  }
}

function getHoveredMesh() {
  if (!modelGroup) return null;
  raycaster.setFromCamera({ x: 0, y: 0 }, camera);
  const meshes = [];
  modelGroup.traverse((child) => { if (child.isMesh) meshes.push(child); });
  const hits = raycaster.intersectObjects(meshes);
  if (hits.length > 0) {
    const m = hits[0].object;
    console.log('[Raycast] Hit:', 'key=' + getMeshKey(m), 'name=' + (m.name || '?'), 'visible=' + m.visible, 'parent=' + (m.parent ? m.parent.name || 'unnamed' : 'none'));
    return m;
  }
  console.log('[Raycast] No hit');
  return null;
}

function setMeshColor(mesh, color) {
  if (!mesh || !mesh.material) return;
  const mats = Array.isArray(mesh.material) ? mesh.material : [mesh.material];
  mats.forEach(m => { if (m && m.color) m.color.set(color || '#8b5cf6'); });
}

function applyManipToGroup(group, color) {
  let idx = 0;
  group.traverse(c => {
    if (!c.isMesh) return;
    if (defaultManip.random_color) {
      setMeshColor(c, randomColor(idx));
    } else {
      setMeshColor(c, color);
    }
    c.material.roughness = defaultManip.roughness ?? 0.7;
    c.material.metalness = defaultManip.metalness ?? 0.1;
    if (c.material.wireframe !== undefined) c.material.wireframe = defaultManip.style === 'wireframe';
    idx++;
  });
}

function recolorHovered(e) {
  if (manipList.length) {
    manipIndex = (manipIndex + 1) % manipList.length;
    Object.assign(defaultManip, manipList[manipIndex]);
  }
  const color = defaultManip.color || '#8b5cf6';
  if (!e.shiftKey) {
    const placed = getHoveredPlacedMesh();
    if (placed) {
      const pIdx = placedObjects.findIndex(p => p.group === placed);
      if (pIdx !== -1 && placedObjects[pIdx].textureId) { setStats('Has texture, skip recolor'); return; }
      applyManipToGroup(placed, color);
      setStats('Recolored placed object');
      markDirty();
      return;
    }
  }
  if (selectedObject && !isMainModelChild(selectedObject) && selectedObject !== modelGroup) {
    const sIdx = placedObjects.findIndex(p => p.group === selectedObject);
    if (sIdx !== -1 && placedObjects[sIdx].textureId) { setStats('Has texture, skip recolor'); return; }
    applyManipToGroup(selectedObject, color);
    setStats('Recolored selected');
    markDirty();
    return;
  }
  const mesh = getHoveredMesh();
  if (mesh) {
    const key = getMeshKey(mesh);
    if (modelTextures[key]) { setStats('Has texture, skip recolor'); return; }
    setMeshColor(mesh, color);
    mesh.material.roughness = defaultManip.roughness ?? 0.7;
    mesh.material.metalness = defaultManip.metalness ?? 0.1;
    if (mesh.material.wireframe !== undefined) mesh.material.wireframe = defaultManip.style === 'wireframe';
    modelColorOverrides[key] = {
      color: '#' + mesh.material.color.getHexString(),
      roughness: mesh.material.roughness,
      metalness: mesh.material.metalness,
      style: mesh.material.wireframe ? 'wireframe' : 'solid',
    };
    markDirty();
    setStats('Recolored ' + (mesh.name || 'mesh'));
  } else {
    setStats('Nothing to recolor');
  }
}

function hideHovered(e) {
  if (!e.shiftKey) {
    const placed = getHoveredPlacedMesh();
    if (placed) {
      actionHistory.push({ type: 'hide', mesh: placed, isPlaced: true });
      placed.visible = false;
      const pIdx = placedObjects.findIndex(p => p.group === placed);
      if (pIdx !== -1) placedObjects[pIdx].hidden = true;
      markDirty();
      setStats('Hidden placed object');
      return;
    }
  }
  const mesh = getHoveredMesh();
  if (!mesh) { setStats('Nothing to hide'); return; }
  const key = getMeshKey(mesh);
  actionHistory.push({ type: 'hide', mesh, isPlaced: false });
  mesh.visible = false;
  modelHiddenMeshes[key] = true;
  markDirty();
  setStats('Hidden mesh');
}

function duplicateHovered(e) {
  if (!modelLoaded) return;
  let pIdx = -1;
  if (!e.shiftKey) {
    const placed = getHoveredPlacedMesh();
    if (placed) pIdx = placedObjects.findIndex(p => p.group === placed);
  }
  if (pIdx === -1 && selectedObject && !isMainModelChild(selectedObject) && selectedObject !== modelGroup) {
    pIdx = placedObjects.findIndex(p => p.group === selectedObject);
  }
  if (pIdx === -1) {
    const mesh = e.shiftKey ? null : getHoveredMesh();
    if (mesh && isMainModelChild(mesh)) {
      const key = getMeshKey(mesh);
      const clone = mesh.clone();
      clone.frustumCulled = true;
      if (clone.material) {
        const mats = Array.isArray(clone.material) ? clone.material : [clone.material];
        clone.material = mats.map(m => m.clone());
        if (clone.material.length === 1) clone.material = clone.material[0];
      }
      const posAttr = clone.geometry.getAttribute('position');
      const normAttr = clone.geometry.getAttribute('normal');
      const uvAttr = clone.geometry.getAttribute('uv');
      const idxAttr = clone.geometry.index;
      const geometryData = {
        positions: Array.from(posAttr.array),
        normals: normAttr ? Array.from(normAttr.array) : [],
        uvs: uvAttr ? Array.from(uvAttr.array) : [],
        indices: idxAttr ? Array.from(idxAttr.array) : [],
      };
      const dir = new THREE.Vector3(0, 0, -1).applyQuaternion(camera.quaternion);
      clone.position.copy(mesh.position).add(dir.multiplyScalar(0.5));
      scene.add(clone);
      placedObjects.push({ group: clone, file_ids: [], geometryData: geometryData, textureId: modelTextures[key] || null });
      updateObjectList();
      actionHistory.push({ type: 'duplicate', mesh: clone });
      if (selectedObject) clearSelection();
      selectObject(clone);
      markDirty();
      setStats('Duplicated submesh');
      return;
    }
    setStats('Nothing to duplicate');
    return;
  }
  const orig = placedObjects[pIdx];
  const clone = orig.group.clone();
  clone.traverse(c => { if (c.isMesh && c.material) c.material = c.material.clone(); });
  clone.position.x += 0.5;
  clone.position.z += 0.5;
  scene.add(clone);
  placedObjects.push({
    group: clone, file_ids: orig.file_ids,
    textureId: orig.textureId || null, uploadedTexturePath: orig.uploadedTexturePath || null,
  });
  updateObjectList();
  actionHistory.push({ type: 'duplicate', mesh: clone });
  if (selectedObject) clearSelection();
  selectObject(clone);
  markDirty();
  setStats('Duplicated object');
}

function getHoveredPlacedMesh() {
  raycaster.setFromCamera({ x: 0, y: 0 }, camera);
  const all = [];
  scene.traverse((c) => {
    if (c.isMesh && !isGizmoChild(c) && !isMainModelChild(c)) all.push(c);
  });
  if (selectedObject && selectedObject.parent === transformControls) {
    selectedObject.traverse(c => { if (c.isMesh) all.push(c); });
  }
  const hits = raycaster.intersectObjects(all);
  if (hits.length === 0) return null;
  let obj = hits[0].object;
  while (obj.parent && obj.parent !== scene && obj.parent !== transformControls) obj = obj.parent;
  return obj;
}

function deleteHovered(e) {
  if (!e.shiftKey) {
    const placed = getHoveredPlacedMesh();
    if (placed) {
      actionHistory.push({ type: 'delete', mesh: placed, parent: placed.parent, file_ids: null });
      placed.parent.remove(placed);
      const idx = placedObjects.findIndex(p => p.group === placed);
      if (idx !== -1) {
        actionHistory[actionHistory.length - 1].file_ids = placedObjects[idx].file_ids;
        placedObjects.splice(idx, 1);
        updateObjectList();
      }
      markDirty();
      setStats('Deleted placed object');
      return;
    }
  }
  const mesh = getHoveredMesh();
  if (!mesh) { setStats('Nothing to delete'); return; }
  const key = getMeshKey(mesh);
  actionHistory.push({ type: 'delete', mesh, parent: mesh.parent });
  mesh.parent.remove(mesh);
  modelDeletedMeshes[key] = true;
  markDirty();
  setStats('Deleted mesh');
}

function undo() {
  const action = actionHistory.pop();
  if (!action) return;
  if (action.type === 'hide') {
    action.mesh.visible = true;
    if (action.isPlaced) {
      const pIdx = placedObjects.findIndex(p => p.group === action.mesh);
      if (pIdx !== -1) delete placedObjects[pIdx].hidden;
    } else {
      const key = getMeshKey(action.mesh);
      delete modelHiddenMeshes[key];
    }
  } else if (action.type === 'delete') {
    action.parent.add(action.mesh);
    if (action.file_ids) {
      placedObjects.push({ group: action.mesh, file_ids: action.file_ids });
    } else {
      const key = getMeshKey(action.mesh);
      delete modelDeletedMeshes[key];
    }
    updateObjectList();
    markDirty();
  } else if (action.type === 'duplicate') {
    action.mesh.traverse(child => {
      if (!child.isMesh) return;
      if (child.geometry) child.geometry.dispose();
      if (child.material) {
        const mats = Array.isArray(child.material) ? child.material : [child.material];
        mats.forEach(m => m.dispose());
      }
    });
    scene.remove(action.mesh);
    const idx = placedObjects.findIndex(p => p.group === action.mesh);
    if (idx !== -1) placedObjects.splice(idx, 1);
    markDirty();
  }
}

// Resize
window.addEventListener('resize', () => {
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, window.innerHeight);
});

// ── Session management ──
const sessionsDropdown = document.getElementById('sessions-dropdown');
const sessionsList = document.getElementById('sessionsList');
const sessionsToggle = document.getElementById('sessionsToggle');
let currentSessionId = null;
let placedObjects = [];

sessionsToggle.addEventListener('click', () => sessionsList.classList.toggle('open'));
document.addEventListener('click', (e) => {
  if (!sessionsDropdown.contains(e.target)) sessionsList.classList.remove('open');
});

let modelColorOverrides = {};
let modelDeletedMeshes = {};
let modelHiddenMeshes = {};
let modelTextures = {};
let mainModelTextureFilePath = '';
let placedDeletedMeshes = {};
let objListEntries = [];

function getMeshKey(mesh) {
  if (mesh.name) return mesh.name;
  const geo = mesh.geometry;
  if (geo && geo.attributes && geo.attributes.position) {
    const pos = geo.attributes.position;
    const vertCount = pos.count;
    const idxCount = geo.index ? geo.index.count : 0;
    let hash = 0;
    for (let i = 0; i < Math.min(3, vertCount); i++) {
      hash = ((hash << 5) - hash) + (pos.getX(i) * 1000 | 0);
      hash |= 0;
      hash = ((hash << 5) - hash) + (pos.getY(i) * 1000 | 0);
      hash |= 0;
      hash = ((hash << 5) - hash) + (pos.getZ(i) * 1000 | 0);
      hash |= 0;
    }
    return 'geo_' + vertCount + '_' + idxCount + '_' + Math.abs(hash);
  }
  let idx = 0, found = false;
  modelGroup.traverse(c => { if (c.isMesh) { if (c === mesh) found = true; if (!found) idx++; } });
  return '_idx_' + idx;
}

function handleTextureLoad(tex) {
  tex.needsUpdate = true;
  console.log('[Texture] handleTextureLoad fired, tex.image=' + (tex.image ? 'set' : 'null') + (tex.image ? ' complete=' + tex.image.complete + ' naturalWH=' + tex.image.naturalWidth + 'x' + tex.image.naturalHeight : '') + ' pending=' + (tex._pendingClones ? tex._pendingClones.length : 0));
  if (tex._pendingClones) {
    tex._pendingClones.forEach(m => {
      if (m.map) {
        m.map.image = tex.image;
        m.map.needsUpdate = true;
        m.needsUpdate = true;
      }
    });
    delete tex._pendingClones;
  }
}

function applyTextureToMaterial(material, texture) {
  if (!texture.image) {
    material.map = texture.clone();
    material.map.wrapS = THREE.RepeatWrapping;
    material.map.wrapT = THREE.RepeatWrapping;
    material.map.needsUpdate = true;
    material.color.setHex(0xffffff);
    material.needsUpdate = true;
    if (!texture._pendingClones) texture._pendingClones = [];
    texture._pendingClones.push(material);
    console.log('[Texture] Cloned pending (image not loaded yet), tracking material');
  } else {
    console.log('[Texture] Creating fresh Texture for loaded image, img.complete=' + texture.image.complete + ' naturalWH=' + texture.image.naturalWidth + 'x' + texture.image.naturalHeight + ' tex.version=' + texture.version + ' tex.uuid=' + texture.uuid);
    if (texture.image.complete === false || texture.image.naturalWidth === 0) {
      console.log('[Texture] Image not fully loaded yet - registering as pending clone');
      material.map = texture.clone();
      material.map.needsUpdate = true;
      material.color.setHex(0xffffff);
      material.needsUpdate = true;
      if (!texture._pendingClones) texture._pendingClones = [];
      texture._pendingClones.push(material);
      return;
    }
    material.map = new THREE.Texture(texture.image);
    material.map.wrapS = THREE.RepeatWrapping;
    material.map.wrapT = THREE.RepeatWrapping;
    material.map.needsUpdate = true;
    material.color.setHex(0xffffff);
    material.needsUpdate = true;
    console.log('[Texture] Fresh Texture, map.image=' + (material.map.image ? 'set' : 'NULL') + ' map.source.uuid=' + material.map.source.uuid + ' map.needsUpdate=' + material.map.needsUpdate + ' map.version=' + material.map.version + ' mat.version=' + material.version + ' map.uuid=' + material.map.uuid);
    var _selfMat = material;
    requestAnimationFrame(function() {
      if (_selfMat.map) {
        _selfMat.map.needsUpdate = true;
        _selfMat.needsUpdate = true;
        console.log('[Texture] rAF refreshed needsUpdate, map.version=' + _selfMat.map.version + ' mat.version=' + _selfMat.version);
      }
    });
  }
}

function applyModelTextures(textures, repeats) {
  if (!textures || !modelGroup) return;
  if (!textureList.length) { setTimeout(() => applyModelTextures(textures, repeats), 300); return; }
  modelGroup.traverse(c => {
    if (!c.isMesh) return;
    const key = getMeshKey(c);
    const tid = textures[key];
    if (!tid) return;
    const tr = textureList.find(t => t.id === tid);
    if (!tr) return;
    if (!textureCache[tr.file_path]) {
      textureCache[tr.file_path] = new THREE.TextureLoader().load('/storage/' + tr.file_path, handleTextureLoad);
    }
    const mat = c.material;
    const mats = Array.isArray(mat) ? mat : [mat];
    mats.forEach(m => applyTextureToMaterial(m, textureCache[tr.file_path]));
    if (repeats && repeats[key]) {
      const rep = repeats[key];
      mats.forEach(m => {
        if (!m.map) return;
        m.map.wrapS = THREE.RepeatWrapping;
        m.map.wrapT = THREE.RepeatWrapping;
        m.map.repeat.set(rep.x, rep.y);
        m.map.needsUpdate = true;
      });
    }
  });
}

function applyMeshOverrides(overrides) {
  if (!overrides || !modelGroup) return;
  modelGroup.traverse(c => {
    if (!c.isMesh) return;
    const key = getMeshKey(c);
    const o = overrides[key];
    if (!o) return;
    c.material = new THREE.MeshStandardMaterial({
      color: new THREE.Color(o.color || '#8b5cf6'),
      roughness: o.roughness ?? 0.7,
      metalness: o.metalness ?? 0.1,
    });
    if (c.material.wireframe !== undefined) c.material.wireframe = (o.style || 'solid') === 'wireframe';
  });
}

function applyDeletedMeshes(deleted) {
  if (!deleted || !modelGroup) { console.log('applyDeletedMeshes: skipped, deleted:', !!deleted, 'modelGroup:', !!modelGroup); return; }
  const toRemove = [];
  modelGroup.traverse(c => {
    if (c === modelGroup) return;
    const key = getMeshKey(c);
    if (deleted[key]) {
      console.log('REMOVING node:', key);
      toRemove.push(c);
    }
  });
  console.log('applyDeletedMeshes: removing', toRemove.length, 'nodes');
  toRemove.forEach(c => c.parent.remove(c));
}

function applyHiddenMeshes(hidden) {
  if (!hidden || !modelGroup) return;
  modelGroup.traverse(c => {
    const key = getMeshKey(c);
    if (hidden[key]) c.visible = false;
  });
}

function clearScene() {
  if (modelGroup) {
    scene.remove(modelGroup);
    modelGroup = null;
  }
  const toRemove = [];
  scene.children.forEach(c => {
    if (!sceneKeep.has(c)) toRemove.push(c);
  });
  toRemove.forEach(c => {
    c.traverse(child => {
      if (!child.isMesh) return;
      if (child.geometry) child.geometry.dispose();
      if (child.material) {
        const mats = Array.isArray(child.material) ? child.material : [child.material];
        mats.forEach(m => m.dispose());
      }
    });
    scene.remove(c);
  });
  modelLoaded = false;
  selectedObject = null;
  prevEmissive = null;
  actionHistory.length = 0;
  placedObjects.length = 0;
  modelColorOverrides = {};
  modelDeletedMeshes = {};
  modelHiddenMeshes = {};
  modelTextures = {};
  mainModelTextureFilePath = '';
  textureTarget = null;
  textureRepeatTarget = null;
  sceneDirty = false;
  changeSerial = 0;
  currentSaveName = '';
  saveBtn.classList.remove('show');
  hudSaveBtn.style.display = 'none';
  saveStatusEl.style.opacity = '0';
  hudSaveStatus.style.display = 'none';
  if (transformControls) transformControls.detach();
  dropzone.style.display = '';
  instructions.classList.remove('show');
  blocker.classList.remove('active');
  controls.unlock();
  setStats('');
  updateObjectList();
}

let sceneDirty = false;
let currentSaveName = '';
const saveStatusEl = document.getElementById('saveStatus');
const saveBtn = document.getElementById('saveBtn');
const hudSaveBtn = document.getElementById('hudSaveBtn');
const hudSaveStatus = document.getElementById('hudSaveStatus');
const saveDialog = document.getElementById('saveDialog');
const saveNameInput = document.getElementById('saveNameInput');
const saveDialogConfirm = document.getElementById('saveDialogConfirm');
const saveDialogCancel = document.getElementById('saveDialogCancel');

let resizeMode = false;
let textureRepeatTarget = null;

function showSaveStatus(msg) {
  saveStatusEl.textContent = msg;
  saveStatusEl.style.opacity = '1';
  hudSaveStatus.textContent = msg;
  hudSaveStatus.style.display = 'inline';
  setTimeout(() => {
    saveStatusEl.style.opacity = '0';
    hudSaveStatus.style.display = 'none';
  }, 2500);
}

function openSaveDialog() {
  saveNameInput.value = currentSaveName || '';
  saveDialog.style.display = 'flex';
  saveNameInput.focus();
  saveNameInput.select();
}

function closeSaveDialog() {
  saveDialog.style.display = 'none';
}

function doSave(name) {
  if (!name) name = 'Untitled';
  console.log('doSave called with name:', name, 'currentSaveName:', currentSaveName);
  closeSaveDialog();
  saveScene(name);
}

saveDialogConfirm.addEventListener('click', () => doSave(saveNameInput.value.trim()));
saveDialogCancel.addEventListener('click', closeSaveDialog);
saveNameInput.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') doSave(saveNameInput.value.trim());
  if (e.key === 'Escape') closeSaveDialog();
});

hudSaveBtn.addEventListener('click', openSaveDialog);

let saveInFlight = false;
let queuedSaveBody = null;
let queuedSaveSerial = 0;
let changeSerial = 0;

function markDirty() {
  sceneDirty = true;
  changeSerial++;
}

function queueSave(body) {
  if (saveInFlight) {
    queuedSaveBody = body;
    queuedSaveSerial = changeSerial;
    return;
  }
  sendSave(body, changeSerial);
}

function sendSave(body, serial) {
  saveInFlight = true;
  queuedSaveBody = null;
  console.log('Saving scene…');
  fetch(SCENES_SAVE_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
    body,
  }).then(async r => {
    const txt = await r.text();
    if (r.ok) {
      if (serial === changeSerial) { sceneDirty = false; console.log('sceneDirty cleared'); }
      else { console.log('serial mismatch, keeping dirty:', serial, 'vs', changeSerial); }
      showSaveStatus('Saved!'); console.log('Save OK:', txt);
    } else {
      showSaveStatus('Save failed (' + r.status + ')'); console.warn('Save failed:', r.status, txt);
    }
    saveInFlight = false;
    if (queuedSaveBody) sendSave(queuedSaveBody, queuedSaveSerial);
  }).catch(e => {
    console.warn('Save scene error:', e);
    showSaveStatus('Save error');
    saveInFlight = false;
    if (queuedSaveBody) sendSave(queuedSaveBody, queuedSaveSerial);
  });
}

function saveScene(name) {
  console.log('saveScene called, name:', name, 'sceneDirty:', sceneDirty, 'placedObjects:', placedObjects.length, 'currentSessionId:', currentSessionId);
  if (!currentSessionId) { console.warn('saveScene: no currentSessionId'); return; }
  if (!sceneDirty && placedObjects.length === 0 && !name) { console.log('saveScene: guard blocked'); return; }
  if (name) currentSaveName = name;
  const objectsData = placedObjects.map(obj => {
    const pos = obj.group.position;
    const rot = obj.group.rotation;
    const scl = obj.group.scale;
    let color = defaultManip.color;
    let roughness = defaultManip.roughness;
    let metalness = defaultManip.metalness;
    let style = defaultManip.style;
    const meshColors = {};
    let meshIdx = 0;
    obj.group.traverse(c => {
      if (c.isMesh && c.material) {
        const mats = Array.isArray(c.material) ? c.material : [c.material];
        for (const mat of mats) {
          if (mat && mat.color) {
            const cStr = '#' + mat.color.getHexString();
            if (meshIdx === 0) color = cStr;
            meshColors['_idx_' + meshIdx] = cStr;
          }
          if (mat && mat.roughness !== undefined) roughness = mat.roughness;
          if (mat && mat.metalness !== undefined) metalness = mat.metalness;
          if (mat && mat.wireframe !== undefined) style = mat.wireframe ? 'wireframe' : 'solid';
        }
        meshIdx++;
      }
    });
    const objEntry = {
      file_ids: obj.file_ids,
      position: [pos.x, pos.y, pos.z],
      rotation: [rot.x, rot.y, rot.z],
      scale: [scl.x, scl.y, scl.z],
      color,
      roughness,
      metalness,
      style,
    };
    if (obj.textureId) {
      objEntry.textureId = obj.textureId;
      obj.group.traverse(c => {
        if (!c.isMesh || !c.material || objEntry.textureRepeat) return;
        const mats = Array.isArray(c.material) ? c.material : [c.material];
        for (const m of mats) {
          if (m.map && m.map.repeat) {
            objEntry.textureRepeat = { x: m.map.repeat.x, y: m.map.repeat.y };
            break;
          }
        }
      });
    }
    if (obj.uploadedTexturePath) objEntry.texturePath = obj.uploadedTexturePath;
    if (obj.hidden) objEntry.hidden = true;
    if (obj.geometryData) objEntry.geometryData = obj.geometryData;
    const vals = Object.values(meshColors);
    if (vals.length > 1 && !vals.every(v => v === vals[0])) objEntry.meshColors = meshColors;
    return objEntry;
  });
  const data = { objects: objectsData };
  if (Object.keys(modelColorOverrides).length) data.modelColors = modelColorOverrides;
  const deletedKeys = Object.keys(modelDeletedMeshes);
  if (deletedKeys.length) data.modelDeleted = deletedKeys;
  const hiddenKeys = Object.keys(modelHiddenMeshes);
  if (hiddenKeys.length) data.modelHidden = hiddenKeys;
  if (Object.keys(modelTextures).length) data.modelTextures = modelTextures;
  const textureRepeats = {};
  if (modelGroup) {
    modelGroup.traverse(c => {
      if (!c.isMesh || !c.material) return;
      const mats = Array.isArray(c.material) ? c.material : [c.material];
      for (const m of mats) {
        if (m.map && m.map.repeat && (m.map.repeat.x !== 1 || m.map.repeat.y !== 1)) {
          const key = getMeshKey(c);
          textureRepeats[key] = { x: m.map.repeat.x, y: m.map.repeat.y };
          break;
        }
      }
    });
  }
  if (Object.keys(textureRepeats).length) {
    data.modelTextureRepeats = textureRepeats;
    console.log('[Save] modelTextureRepeats:', JSON.stringify(textureRepeats));
  }
  if (mainModelTextureFilePath) data.modelTexturePath = mainModelTextureFilePath;
  if (currentSaveName) data.save_name = currentSaveName;
  const body = JSON.stringify({ session_id: currentSessionId, data });
  console.log('Saving scene: objects:', objectsData.length, 'modelDeleted:', JSON.stringify(deletedKeys), 'name:', currentSaveName, 'session_id:', currentSessionId);
  queueSave(body);
}

saveBtn.addEventListener('click', openSaveDialog);

let escUnlockTime = 0;
controls.addEventListener('unlock', () => { escUnlockTime = Date.now(); });

document.addEventListener('keydown', (e) => {
  if ((e.ctrlKey || e.metaKey) && e.key === 's') {
    e.preventDefault();
    markDirty();
    saveScene();
    setStats('Scene saved!');
    return;
  }
  if (e.key === 'l' && modelLoaded) {
    if (selectedObject && !isMainModelChild(selectedObject) && selectedObject !== modelGroup) {
      resizeMode = !resizeMode;
      if (resizeMode) {
        transformControls.detach();
        controls.lock();
        blocker.style.display = '';
        setStats('Resize mode — + / - to adjust size, L to exit');
      } else {
        transformControls.attach(selectedObject);
        controls.unlock();
        blocker.style.display = 'none';
        setStats('Drag gizmo — G/R/S for mode | L to finish');
      }
    }
    e.preventDefault();
    return;
  }
  if ((e.key === '+' || e.key === '=') && selectedObject) {
    const s = selectedObject.scale;
    const f = 1.25;
    selectedObject.scale.set(s.x * f, s.y * f, s.z * f);
    setStats('Size: ' + (s.x * f).toFixed(3));
    markDirty();
    e.preventDefault();
    return;
  }
  if (e.key === '-' && selectedObject) {
    const s = selectedObject.scale;
    const f = 0.8;
    selectedObject.scale.set(s.x * f, s.y * f, s.z * f);
    setStats('Size: ' + (s.x * f).toFixed(3));
    markDirty();
    e.preventDefault();
    return;
  }
  if (e.altKey && (e.key === 'ArrowUp' || e.key === 'ArrowDown' || e.key === 'ArrowLeft' || e.key === 'ArrowRight')) {
    e.preventDefault();
    const speed = 0.15;
    const pos = dirLight.position;
    if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
      const angle = e.key === 'ArrowLeft' ? speed : -speed;
      const x = pos.x * Math.cos(angle) - pos.z * Math.sin(angle);
      const z = pos.x * Math.sin(angle) + pos.z * Math.cos(angle);
      pos.set(x, pos.y, z);
    } else if (e.key === 'ArrowUp') {
      pos.y = Math.min(pos.y + speed, 25);
    } else if (e.key === 'ArrowDown') {
      pos.y = Math.max(pos.y - speed, 0.5);
    }
    dirLight.target.updateMatrixWorld();
    setStats('Light angle: ' + pos.x.toFixed(1) + ', ' + pos.y.toFixed(1) + ', ' + pos.z.toFixed(1));
    return;
  }
  if (e.key === 'b' && textureModal.style.display !== 'flex') {
    const hp = getHoveredPlacedMesh();
    const placedTarget = hp && placedObjects.findIndex(p => p.group === hp) !== -1 ? hp : null;
    textureTarget = placedTarget || (selectedObject && !isMainModelChild(selectedObject) && selectedObject !== modelGroup ? selectedObject : null) || getHoveredMesh();
    if (controls.isLocked) controls.unlock();
    textureModal.style.display = 'flex';
    if (textureList.length) {
      renderTextureGrid();
    } else {
      textureGrid.innerHTML = '<div style="color:rgba(255,255,255,0.4);padding:1rem;font-size:0.8rem;">Loading textures\u2026</div>';
      (function retryTextureGrid() {
        if (textureList.length) { renderTextureGrid(); return; }
        setTimeout(retryTextureGrid, 300);
      })();
    }
    e.preventDefault();
    return;
  }
  if ((e.key === '>' || e.key === '<') && modelLoaded) {
    e.preventDefault();
    const factor = e.key === '>' ? 1.25 : 0.8;
    const target = selectedObject || textureRepeatTarget || getHoveredMesh() || getHoveredPlacedMesh();
    if (!target) { setStats('No target — select or apply texture first'); return; }
    let currentRepeat = 1;
    let found = false;
    target.traverse(c => {
      if (!c.isMesh || !c.material) return;
      const mats = Array.isArray(c.material) ? c.material : [c.material];
      mats.forEach(m => {
        if (!m.map) return;
        m.map.wrapS = THREE.RepeatWrapping;
        m.map.wrapT = THREE.RepeatWrapping;
        m.map.repeat.x *= factor;
        m.map.repeat.y *= factor;
        m.map.needsUpdate = true;
        currentRepeat = m.map.repeat.x;
        found = true;
      });
    });
    if (found) { setStats(`Texture repeat: ${currentRepeat.toFixed(2)}`); markDirty(); }
    else setStats('No texture on this object');
    return;
  }
  if (e.key === 'Escape') {
    if (textureModal.style.display === 'flex') {
      textureModal.style.display = 'none';
      textureTarget = null;
      e.stopPropagation();
      return;
    }
    if (saveDialog.style.display === 'flex') {
      closeSaveDialog();
      e.stopPropagation();
      return;
    }
    if (!controls.isLocked && modelLoaded && Date.now() - escUnlockTime > 300) {
      markDirty();
      saveScene();
      setStats('Scene saved!');
      e.preventDefault();
      e.stopPropagation();
    }
  }
}, true);

const textureModal = document.getElementById('textureModal');
const textureGrid = document.getElementById('textureGrid');
const textureModalClose = document.getElementById('textureModalClose');

textureModalClose.addEventListener('click', () => { textureModal.style.display = 'none', textureTarget = null; });

function renderTextureGrid() {
  textureGrid.innerHTML = '';
  textureList.forEach(t => {
    const div = document.createElement('div');
    div.className = 'thumb';
    div.innerHTML = `<div class="thumb-img-wrap" style="width:100%;height:80px;background:rgba(255,255,255,0.03);display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:rgba(255,255,255,0.1);overflow:hidden;"><img src="/storage/${t.file_path}" alt="${t.name}" loading="lazy" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'" onload="this.parentNode.style.background='none'"></div><div class="label">${t.name}</div>`;
    div.addEventListener('click', () => applyTextureById(t.id));
    textureGrid.appendChild(div);
  });
}
