import * as THREE from 'three';
import { OBJLoader } from 'three/addons/loaders/OBJLoader.js';
import { MTLLoader } from 'three/addons/loaders/MTLLoader.js';
import { PointerLockControls } from 'three/addons/controls/PointerLockControls.js';
import { TransformControls } from 'three/addons/controls/TransformControls.js';

const DEBUG = false;
if (!DEBUG) {
  const noop = () => {};
  console.log = noop;
  console.warn = noop;
  console.error = noop;
}

const UPLOAD_URL = '/app/upload';
const SESSIONS_URL = '/app/sessions';
const MANIPULATIONS_URL = '/app/manipulations';
const SCENES_SAVE_URL = '/app/scenes/save';
const SCENES_LOAD_URL = '/app/scenes';
const PLANS_URL = '/app/plans';
const LIMITS_URL = '/app/limits';
const UPGRADE_REQUEST_URL = '/app/upgrade-request';
const SOCIAL_DISCOUNTS_URL = '/app/social-discounts';
const SOCIAL_POSTS_URL = '/app/social-posts';
const CSRF_TOKEN = '{{ csrf_token() }}';
const USER_NAME = '{{ auth()->user()->name }}';
const USER_EMAIL = '{{ auth()->user()->email }}';
const USER_ROLE = '{{ auth()->user()->role }}';

let userLimits = null;
let plansList = [];
let socialDiscountsList = [];
let userPostedIds = [];
let upgradeModalType = null;

const splash = document.getElementById('splash');
const splashStatus = document.getElementById('splash-status');
const splashFill = document.getElementById('splash-fill');
const splashSparks = document.getElementById('splash-sparks');

const blocker = document.getElementById('blocker');
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');
const instructions = document.getElementById('instructions');
const statsEl = document.getElementById('stats');
let statsTimer = null;
let statsLocked = false;
function setStats(msg) {
  statsEl.textContent = msg;
  statsEl.style.opacity = '1';
  statsLocked = true;
  if (statsTimer) clearTimeout(statsTimer);
  statsTimer = setTimeout(() => {
    statsLocked = false;
    statsEl.style.opacity = '0.3';
  }, 3000);
}
const defaultManip = { color: '#8b5cf6', scale: 1, roughness: 0.7, metalness: 0.1, style: 'solid', random_color: false };

function randomColor(seed) {
  const colors = defaultManip.colors;
  if (colors && colors.length) {
    return new THREE.Color(colors[seed % colors.length]);
  }
  const hue = ((seed * 0.618033988749895) % 1.0 + 1.0) % 1.0;
  return new THREE.Color().setHSL(hue, 0.65, 0.5);
}
let manipList = [];
let manipIndex = 0;
let textureList = [];
let textureCache = {};
const TEXTURES_URL = '/app/textures';

fetch(MANIPULATIONS_URL, { headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } })
  .then(r => r.json())
  .then(list => {
    manipList = list;
    if (list.length) Object.assign(defaultManip, list[0]);
  })
  .catch(() => {});

const KEYBINDINGS_URL = '/app/keybindings';
let keyActionMap = {};
let keyConditionalMap = {};
let keyMovementMap = {};

function buildKeyMaps(bindings) {
  const actionHandlers = {
    recolor: recolorHovered, select: selectHovered, duplicate: duplicateHovered,
    place: placeObjectClick, delete: deleteHovered, undo: undo,
    inspect: logObjectSize,
  };
  const conditionalHandlers = {
    translate: () => { if (selectedObject) transformControls.setMode('translate'); },
    rotate:    () => { if (selectedObject) transformControls.setMode('rotate'); },
    scale:     () => { if (selectedObject) transformControls.setMode('scale'); },
  };
  const movMap = {
    fwd: 'backward', back: 'forward', left: 'left', right: 'right',
    up: 'up', down: 'down', sprint: 'sprint',
  };
  for (const [k, code] of Object.entries(bindings)) {
    const short = k.replace('kbd_', '');
    if (actionHandlers[short]) keyActionMap[code] = actionHandlers[short];
    else if (conditionalHandlers[short]) keyConditionalMap[code] = conditionalHandlers[short];
    else if (movMap[short]) keyMovementMap[code] = movMap[short];
  }
}

function codeToKbd(code) {
  if (code === 'Delete') return 'Del';
  if (code.startsWith('Key')) return code.slice(3);
  if (code.startsWith('Shift')) return 'Shift';
  return code;
}

const keyDisplayLines = [
  { items: ['fwd','left','back','right'], suffix: 'Move', group: true },
  { items: ['up','down'], suffix: 'Up / Down', group: true },
  { items: ['sprint'], suffix: 'Sprint' },
  { items: ['recolor'], suffix: 'Recolor' },
  { items: ['duplicate'], suffix: 'Duplicate' },
  { items: ['delete'], suffix: 'Delete' },
  { items: ['undo'], suffix: 'Undo' },
  { items: ['select'], suffix: 'Select' },
  { items: ['place'], suffix: 'Place object' },
  { items: ['translate','rotate','scale'], suffix: 'Move / Rotate / Scale', group: true },
  { items: ['l'], suffix: 'Resize mode (+ / -)' },
  { items: ['b'], suffix: 'Textures' },
  { items: ['inspect'], suffix: 'Inspect size' },
  { items: ['>','<'], suffix: 'Texture repeat on textured obj', group: true },
];

function updateKeyDisplay(bindings) {
  const hud = document.getElementById('hud-keys');
  const instr = document.getElementById('instr-keys');
  const html = keyDisplayLines.map(l => {
    const kbdStr = l.items.map(k => codeToKbd(bindings['kbd_' + k] || k)).join(l.group ? ' ' : ' ');
    return `<span><kbd>${kbdStr.split(' ').join('</kbd><kbd>')}</kbd> — ${l.suffix}</span>`;
  }).join('');
  const esc = '<span><kbd>Esc</kbd> — Release cursor</span>';
  if (instr) instr.innerHTML = html + esc;
  if (hud) {
    hud.innerHTML = keyDisplayLines.map(l => {
      const kbdStr = l.items.map(k => codeToKbd(bindings['kbd_' + k] || k)).join('/');
      return `<p>${kbdStr} ${l.suffix.toLowerCase()}</p>`;
    }).join('');
  }
}

const defaultBindings = {
  kbd_recolor: 'KeyK', kbd_select: 'KeyL', kbd_duplicate: 'KeyH', kbd_place: 'KeyO',
  kbd_delete: 'Delete', kbd_undo: 'KeyZ', kbd_translate: 'KeyG', kbd_rotate: 'KeyR',
  kbd_scale: 'KeyT', kbd_fwd: 'KeyW', kbd_back: 'KeyS', kbd_left: 'KeyA',
  kbd_right: 'KeyD', kbd_up: 'KeyE', kbd_down: 'KeyQ', kbd_sprint: 'ShiftLeft',
  kbd_inspect: 'KeyI',
};

function applyBindings(bindings) {
  const merged = { ...defaultBindings, ...bindings };
  buildKeyMaps(merged);
  updateKeyDisplay(merged);
}

fetch(KEYBINDINGS_URL)
  .then(r => r.json())
  .then(bindings => applyBindings(bindings))
  .catch(() => applyBindings({}));

fetch(TEXTURES_URL)
  .then(r => r.json())
  .then(list => { textureList = list; })
  .catch(() => {});

let splashPending = 0;
let splashQueue = [];
let splashActive = false;

function spawnSpark() {
  const el = document.createElement('div');
  el.className = 'splash-spark';
  const x = Math.random() * window.innerWidth;
  const y = Math.random() * window.innerHeight;
  const dx = (Math.random() - 0.5) * 120;
  const dy = -Math.random() * 120 - 20;
  el.style.left = x + 'px';
  el.style.top = y + 'px';
  el.style.setProperty('--dx', dx + 'px');
  el.style.setProperty('--dy', dy + 'px');
  splashSparks.appendChild(el);
  setTimeout(() => el.remove(), 1200);
}

function showSplash() {
  splashActive = true;
  splashPending = 0;
  splash.classList.remove('hidden');
  splashStatus.textContent = 'Loading\u2026';
  splashFill.style.width = '0%';
  if (!window._splashSparkInt) window._splashSparkInt = setInterval(spawnSpark, 400);
}

function splashProgress(msg) {
  splashStatus.textContent = msg;
}

function splashReady(onDone) {
  if (window._splashSparkInt) { clearInterval(window._splashSparkInt); window._splashSparkInt = null; }
  splashStatus.textContent = 'Ready!';
  splashFill.style.width = '100%';
  splashActive = false;
  splashPending = 0;
  setTimeout(() => {
    splash.classList.add('hidden');
    if (onDone) onDone();
  }, 400);
}

function showSizeHint() {
  const el = document.getElementById('sizeHint');
  if (!el) return;
  el.style.display = 'block';
}

function hideSizeHint() {
  const el = document.getElementById('sizeHint');
  if (!el) return;
  el.style.display = 'none';
}

function setStatsCount() {
  var total = 0;
  if (modelGroup) modelGroup.traverse(function(c) { if (c.isMesh) total++; });
  var base = statsEl.textContent.replace(/\s+\d+ objects?$/, '');
  statsEl.textContent = base + (base ? ' ' : '') + total + ' objects';
}
