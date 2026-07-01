function applyTextureById(textureId) {
  const tr = textureList.find(t => t.id === textureId);
  if (!tr) return;
  if (!textureCache[tr.file_path]) {
    textureCache[tr.file_path] = new THREE.TextureLoader().load('/storage/' + tr.file_path, handleTextureLoad);
  }
  const tex = textureCache[tr.file_path];
  const target = textureTarget;
  textureTarget = null;
  if (!target) { setStats('No object under crosshair'); return; }
  const pIdx = placedObjects.findIndex(p => p.group === target);
  if (pIdx !== -1) {
    console.log('[Texture] Applying to placed object #' + pIdx);
    target.traverse(c => {
      if (c.isMesh && c.material) {
        const mats = Array.isArray(c.material) ? c.material : [c.material];
        mats.forEach(m => applyTextureToMaterial(m, tex));
      }
    });
    placedObjects[pIdx].textureId = textureId;
  } else if (isMainModelChild(target)) {
    const key = getMeshKey(target);
    const hasUV = target.geometry && target.geometry.attributes && target.geometry.attributes.uv;
    console.log('[Texture] Applying to main mesh key=' + key, 'name=' + (target.name || '?'), 'type=' + target.type, 'visible=' + target.visible, 'hasUV=' + !!hasUV, 'parent=' + (target.parent ? target.parent.name || 'unnamed' : 'none'), 'tex.image=' + (tex.image ? 'set' : 'null'), 'tex.needsUpdate=' + tex.needsUpdate, 'tex.uuid=' + tex.uuid, 'tex.version=' + tex.version, 'cached=' + (!!textureCache[tr.file_path]));
    const mats = Array.isArray(target.material) ? target.material : [target.material];
    mats.forEach(m => {
      console.log('[Texture] Material before apply: map=' + (m.map ? 'set(' + m.map.uuid + ')' : 'null') + ' version=' + m.version + ' type=' + m.type);
      applyTextureToMaterial(m, tex);
    });
    modelTextures[key] = textureId;
    delete modelColorOverrides[key];
  } else {
    setStats('No object under crosshair');
    return;
  }
  textureModal.style.display = 'none';
  textureRepeatTarget = target;
  markDirty();
}

function applyTextureRepeat(obj, repeat) {
  if (!repeat) { console.warn('[applyTextureRepeat] called with null repeat'); return; }
  console.log('[applyTextureRepeat] setting repeat to', JSON.stringify(repeat));
  obj.traverse(c => {
    if (!c.isMesh || !c.material) return;
    const mats = Array.isArray(c.material) ? c.material : [c.material];
    mats.forEach(m => {
      if (!m.map) return;
      m.map.wrapS = THREE.RepeatWrapping;
      m.map.wrapT = THREE.RepeatWrapping;
      m.map.repeat.set(repeat.x, repeat.y);
      m.map.needsUpdate = true;
    });
  });
}

function loadSceneObjects() {
  if (!currentSessionId) return;
  fetch(`${SCENES_LOAD_URL}/${currentSessionId}`, {
    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
  })
    .then(r => r.json())
    .then(data => {
      console.log('Load scene response:', JSON.stringify(data));
      if (!data.objects) { console.log('Load scene: no objects data', data); return; }
      currentSaveName = data.save_name || '';
      if (data.modelDeleted) {
        console.log('Applying modelDeleted:', JSON.stringify(data.modelDeleted));
        const del = {};
        data.modelDeleted.forEach(k => del[k] = true);
        applyDeletedMeshes(del);
        modelDeletedMeshes = del;
        console.log('modelDeletedMeshes set to:', JSON.stringify(modelDeletedMeshes));
      } else {
        console.log('No modelDeleted in scene data');
      }
      if (data.modelHidden) {
        console.log('Applying modelHidden:', JSON.stringify(data.modelHidden));
        const hidden = {};
        data.modelHidden.forEach(k => hidden[k] = true);
        applyHiddenMeshes(hidden);
        modelHiddenMeshes = hidden;
      }
      if (data.modelColors) {
        applyMeshOverrides(data.modelColors);
        modelColorOverrides = {...data.modelColors};
      }
      if (data.modelTextures) {
        applyModelTextures(data.modelTextures, data.modelTextureRepeats || null);
        modelTextures = {...data.modelTextures};
      }
      if (data.modelTexturePath) {
        mainModelTextureFilePath = data.modelTexturePath;
        const url = '/storage/' + data.modelTexturePath;
        if (!textureCache[data.modelTexturePath]) {
          textureCache[data.modelTexturePath] = new THREE.TextureLoader().load(url, handleTextureLoad);
        }
        const tex = textureCache[data.modelTexturePath];
        modelGroup.traverse(c => {
          if (c.isMesh && c.material) {
            const mats = Array.isArray(c.material) ? c.material : [c.material];
            mats.forEach(m => applyTextureToMaterial(m, tex));
          }
        });
      }
      saveBtn.classList.add('show');
      hudSaveBtn.style.display = 'inline';
      if (currentSaveName) setStats('Loaded: ' + currentSaveName);
      updateObjectList();
      console.log('Loading', data.objects.length, 'scene objects, name:', currentSaveName);
      var _loadTotal = 0;
      var _loadDone = 0;
      data.objects.forEach(function(o) { if (!o.geometryData) _loadTotal++; });
      function oneObjDone() { _loadDone++; if (_loadDone >= _loadTotal) { console.log('[Load] All scene objects loaded'); splashReady(); } }
      if (_loadTotal === 0) { splashReady(); }
      data.objects.forEach(objData => {
        if (objData.geometryData) {
          const gd = objData.geometryData;
          const geo = new THREE.BufferGeometry();
          geo.setAttribute('position', new THREE.Float32BufferAttribute(gd.positions, 3));
          if (gd.normals && gd.normals.length) geo.setAttribute('normal', new THREE.Float32BufferAttribute(gd.normals, 3));
          if (gd.uvs && gd.uvs.length) geo.setAttribute('uv', new THREE.Float32BufferAttribute(gd.uvs, 2));
          if (gd.indices && gd.indices.length) geo.setIndex(gd.indices);
          const mat = new THREE.MeshStandardMaterial({
            color: new THREE.Color(objData.color || '#8b5cf6'),
            roughness: objData.roughness ?? 0.7,
            metalness: objData.metalness ?? 0.1,
          });
          if (mat.wireframe !== undefined) mat.wireframe = (objData.style || 'solid') === 'wireframe';
          const mesh = new THREE.Mesh(geo, mat);
          mesh.castShadow = true;
          mesh.receiveShadow = true;
          mesh.frustumCulled = true;
          if (objData.position) mesh.position.set(objData.position[0], objData.position[1], objData.position[2]);
          if (objData.scale) mesh.scale.set(objData.scale[0], objData.scale[1], objData.scale[2]);
          if (objData.rotation) mesh.rotation.set(objData.rotation[0], objData.rotation[1], objData.rotation[2]);
          scene.add(mesh);
          if (objData.hidden) mesh.visible = false;
          placedObjects.push({ group: mesh, file_ids: objData.file_ids || [], geometryData: gd, textureId: objData.textureId || null, uploadedTexturePath: objData.texturePath || null, hidden: objData.hidden || false });
          if (objData.textureId) {
            (function tryGeoTexture() {
              const tr = textureList.find(t => t.id === objData.textureId);
              if (!tr) { setTimeout(tryGeoTexture, 300); return; }
              if (!textureCache[tr.file_path]) {
                textureCache[tr.file_path] = new THREE.TextureLoader().load('/storage/' + tr.file_path, handleTextureLoad);
              }
              applyTextureToMaterial(mat, textureCache[tr.file_path]);
              if (objData.textureRepeat) {
                mat.map.wrapS = THREE.RepeatWrapping;
                mat.map.wrapT = THREE.RepeatWrapping;
                mat.map.repeat.set(objData.textureRepeat.x, objData.textureRepeat.y);
                mat.map.needsUpdate = true;
              }
            })();
          }
          if (objData.texturePath) {
            const tUrl = '/storage/' + objData.texturePath;
            if (!textureCache[objData.texturePath]) {
              textureCache[objData.texturePath] = new THREE.TextureLoader().load(tUrl, handleTextureLoad);
            }
            applyTextureToMaterial(mat, textureCache[objData.texturePath]);
          }
          updateObjectList();
          console.log('[Load] Reconstructed submesh duplicate from geometryData');
          return;
        }
        const objFileUrl = objData.files.find(f => f.original_name.toLowerCase().endsWith('.obj'));
        if (!objFileUrl) { console.warn('No .obj URL for object', objData); oneObjDone(); return; }
        const objLoader = new OBJLoader();
        fetch(objFileUrl.url)
          .then(r => r.text())
          .then(text => {
            const obj = objLoader.parse(text);
            obj.traverse(child => {
              if (child.isMesh) {
                child.castShadow = true;
                child.receiveShadow = true;
                child.frustumCulled = true;
                child.material = new THREE.MeshStandardMaterial({
                  color: new THREE.Color(objData.color || '#8b5cf6'),
                  roughness: objData.roughness ?? 0.7,
                  metalness: objData.metalness ?? 0.1,
                });
                if (child.material.wireframe !== undefined) child.material.wireframe = (objData.style || 'solid') === 'wireframe';
              }
            });
            if (objData.meshColors) {
              let mIdx = 0;
              obj.traverse(child => {
                if (child.isMesh) {
                  const mc = objData.meshColors['_idx_' + mIdx];
                  if (mc && child.material) child.material.color.set(mc);
                  mIdx++;
                }
              });
            }
            if (objData.textureId) {
              const tryTexture = () => {
                const tr = textureList.find(t => t.id === objData.textureId);
                if (!tr) { setTimeout(tryTexture, 300); return; }
                if (!textureCache[tr.file_path]) {
                  textureCache[tr.file_path] = new THREE.TextureLoader().load('/storage/' + tr.file_path, handleTextureLoad);
                }
                obj.traverse(child => {
                  if (child.isMesh && child.material) {
                    const mats = Array.isArray(child.material) ? child.material : [child.material];
                    mats.forEach(m => applyTextureToMaterial(m, textureCache[tr.file_path]));
                  }
                });
                if (objData.textureRepeat) {
                  console.log('[Load] applying textureRepeat:', JSON.stringify(objData.textureRepeat));
                  applyTextureRepeat(obj, objData.textureRepeat);
                }
              };
              tryTexture();
            }
            if (objData.texturePath) {
              const tUrl = '/storage/' + objData.texturePath;
              if (!textureCache[objData.texturePath]) {
                textureCache[objData.texturePath] = new THREE.TextureLoader().load(tUrl, handleTextureLoad);
              }
              const tex = textureCache[objData.texturePath];
              obj.traverse(child => {
                if (child.isMesh && child.material) {
                  const mats = Array.isArray(child.material) ? child.material : [child.material];
                  mats.forEach(m => applyTextureToMaterial(m, tex));
                }
              });
            }
            if (objData.position) obj.position.set(objData.position[0], objData.position[1], objData.position[2]);
            if (objData.scale) obj.scale.set(objData.scale[0], objData.scale[1], objData.scale[2]);
            if (objData.rotation) obj.rotation.set(objData.rotation[0], objData.rotation[1], objData.rotation[2]);
            scene.add(obj);
            if (objData.hidden) obj.visible = false;
            placedObjects.push({ group: obj, file_ids: objData.file_ids, textureId: objData.textureId || null, uploadedTexturePath: objData.texturePath || null, hidden: objData.hidden || false });
            updateObjectList();
            console.log('Loaded placed object, color:', objData.color);
            oneObjDone();
          })
          .catch(e => { console.warn('Failed to load placed object OBJ:', e); oneObjDone(); });
      });
    })
    .catch(e => console.warn('Load scene failed:', e));
}

function fetchSessions() {
  fetch(SESSIONS_URL, {
    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
  })
    .then(r => r.json())
    .then(sessions => renderSessions(sessions))
    .catch(() => {});
}

function renderSessions(sessions) {
  sessionsList.innerHTML = '';
  if (!sessions.length) {
    const el = document.createElement('div');
    el.className = 'no-sessions';
    el.textContent = 'No previous sessions';
    sessionsList.appendChild(el);
    sessionsDropdown.classList.remove('hidden');
    return;
  }
  sessionsDropdown.classList.remove('hidden');
  sessions.forEach(s => {
    const item = document.createElement('button');
    item.className = 'sess-item';
    if (s.session_id === currentSessionId) item.classList.add('active');
    const date = new Date(s.last_upload);
    const files = s.files.split(',').map(f => f.trim());
    const label = files.filter(f => f.endsWith('.obj')).join(', ') || files.join(', ');
    const saveNameHtml = s.save_name
      ? `<span class="save-label">${s.save_name}</span>`
      : `<span class="no-save">unsaved</span>`;
    item.innerHTML = `${saveNameHtml}<span class="file-label">${label}</span> <span class="time">${date.toLocaleDateString()} ${date.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'})}</span>`;
    item.dataset.sessionId = s.session_id;
    item.addEventListener('click', () => {
      loadSessionFiles(s.session_id);
      sessionsList.classList.remove('open');
    });
    sessionsList.appendChild(item);
  });
}

function loadSessionFiles(sessionId) {
  console.log('loadSessionFiles:', sessionId);
  clearScene();
  showSplash();
  splashProgress('Loading session\u2026');
  currentSessionId = sessionId;
  sessionsList.querySelectorAll('.sess-item').forEach(b => b.classList.toggle('active', b.dataset.sessionId === sessionId));
  fetch(`/app/sessions/${sessionId}/files`, {
    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
  })
    .then(r => r.json())
    .then(files => {
      const objFile = files.find(f => f.original_name.toLowerCase().endsWith('.obj'));
      const mtlFile = files.find(f => f.original_name.toLowerCase().endsWith('.mtl'));
      if (!objFile) return;
      const objUrl = objFile.url;
      const mtlUrl = mtlFile ? mtlFile.url : null;
      loadRemoteObj(objUrl, mtlUrl, objFile.original_name.replace(/\.obj$/i, ''));
    })
    .catch(() => {});
}

function loadRemoteObj(objUrl, mtlUrl, modelName) {
  var _sceneLoadDone = false;

  function sceneReady() {
    if (_sceneLoadDone) return;
    _sceneLoadDone = true;
    hideSizeHint();
    dropzone.style.display = 'none';
    instructions.classList.add('show');
    saveBtn.classList.add('show');
    hudSaveBtn.style.display = 'inline';
    splashProgress('Loading scene objects\u2026');
    loadSceneObjects();
  }

  const objLoader = new OBJLoader();

  function parseRemoteObj(text) {
    try {
      const obj = objLoader.parse(text);
      let idx = 0;
      obj.traverse((child) => {
        if (child.isMesh) {
          child.castShadow = true;
          child.receiveShadow = true;
          child.frustumCulled = true;
        const base = manipList.length ? manipList[0] : defaultManip;
        const meshColor = (base.random_color || defaultManip.random_color) ? randomColor(idx) : new THREE.Color(base.color);
        child.material = new THREE.MeshStandardMaterial({
          color: meshColor,
          roughness: base.roughness,
          metalness: base.metalness,
        });
        idx++;
        }
      });
      scene.add(obj);
      modelGroup = obj;
      modelLoaded = true;
      setStats('Model loaded');
      sceneReady();
    } catch (e) {
      console.warn('Failed to parse OBJ:', e);
      setStats('Failed to load model');
    }
  }

  function fetchText(url) {
    return fetch(url).then(r => {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.text();
    });
  }

  if (mtlUrl) {
    fetchText(mtlUrl)
      .then(mtlText => {
        mtlText = mtlText.replace(/map_\w+\s+\S*\.tga\b/gi, '');
        const mtlLoader = new MTLLoader();
        const materials = mtlLoader.parse(mtlText);
        objLoader.setMaterials(materials);
        return fetchText(objUrl);
      })
      .then(objText => parseRemoteObj(objText))
      .catch(() => fetchText(objUrl).then(parseRemoteObj).catch(e => {
        console.warn('Failed to load session files:', e);
        setStats('Failed to load session');
      }));
  } else {
    fetchText(objUrl)
      .then(parseRemoteObj)
      .catch(e => {
        console.warn('Failed to load session file:', e);
        setStats('Failed to load session');
      });
  }
}
