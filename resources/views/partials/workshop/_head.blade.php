<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>3D Walkthrough - Scanned Room</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Segoe UI', system-ui, sans-serif; overflow: hidden; background: #000; }
canvas { display: block; }

#splash {
  position: fixed; inset: 0; z-index: 30;
  background: radial-gradient(ellipse at center, #0f0f1a 0%, #000 100%);
  display: flex; align-items: center; justify-content: center;
  transition: opacity 0.6s;
}
#splash.hidden { opacity: 0; pointer-events: none; }

#splash-content {
  text-align: center; color: #fff; user-select: none;
}

#splash-icon {
  font-size: 4rem; display: inline-block;
  animation: hammerSwing 1.5s ease-in-out infinite;
  filter: drop-shadow(0 0 20px rgba(255,170,80,0.3));
}
@keyframes hammerSwing {
  0%, 100% { transform: rotate(-20deg) scale(1); }
  50% { transform: rotate(20deg) scale(1.1); }
}

#splash-title {
  font-size: 1.6rem; font-weight: 300; letter-spacing: 3px;
  margin-top: 1.5rem; margin-bottom: 0.3rem;
  text-transform: uppercase;
}
#splash-title span { opacity: 0.3; font-weight: 100; }

#splash-status {
  font-size: 0.85rem; opacity: 0.5; letter-spacing: 1px;
  margin-bottom: 2rem; min-height: 1.2rem;
  transition: opacity 0.3s;
}

#splash-bar {
  width: 200px; height: 2px; margin: 0 auto;
  background: rgba(255,255,255,0.08);
  border-radius: 2px; overflow: hidden;
}
#splash-fill {
  height: 100%; width: 0%;
  background: linear-gradient(90deg, #ffaa55, #ff6633);
  border-radius: 2px;
  transition: width 0.3s linear;
}

#splash-sparks {
  position: fixed; inset: 0; pointer-events: none; overflow: hidden;
}
.splash-spark {
  position: absolute; width: 3px; height: 3px;
  background: #ffaa55; border-radius: 50%;
  animation: sparkFade 1.2s ease-out forwards;
}
@keyframes sparkFade {
  0% { opacity: 1; transform: translate(0, 0) scale(1); }
  100% { opacity: 0; transform: translate(var(--dx), var(--dy)) scale(0); }
}

#blocker {
  position: fixed; inset: 0; z-index: 10;
  background: rgba(0,0,0,0.6);
  display: flex; align-items: center; justify-content: center;
}
#blocker.active { pointer-events: none; background: transparent; }
#blocker.active #blocker-content { opacity: 0; }

#blocker-content {
  text-align: center; color: #fff;
  transition: opacity 0.4s;
  user-select: none;
}

#sessions-dropdown {
  position: fixed; top: 3rem; left: 1rem; z-index: 25;
  transition: opacity 0.3s;
}
#sessions-dropdown.hidden { opacity: 0; pointer-events: none; }
#sessions-dropdown .toggle {
  background: rgba(10,10,20,0.6);
  backdrop-filter: blur(6px);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 4px;
  padding: 0.3rem 0.8rem;
  color: rgba(255,255,255,0.4);
  font-size: 0.7rem;
  letter-spacing: 1px;
  text-transform: uppercase;
  cursor: pointer;
  transition: color 0.2s, border-color 0.2s;
}
#sessions-dropdown .toggle:hover {
  color: rgba(255,255,255,0.7);
  border-color: rgba(255,255,255,0.2);
}
#sessions-dropdown .list {
  display: none;
  position: absolute; top: 100%; left: 0; margin-top: 0.3rem;
  min-width: 200px; max-height: 250px; overflow-y: auto;
  background: rgba(15,15,30,0.9);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.08);
  border-radius: 6px;
  padding: 0.3rem 0;
}
#sessions-dropdown .list.open { display: block; }
#sessions-dropdown .sess-item {
  display: block; width: 100%;
  background: none; border: none;
  padding: 0.4rem 0.8rem;
  color: rgba(255,255,255,0.5);
  font-size: 0.75rem;
  text-align: left;
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
}
#sessions-dropdown .sess-item:hover {
  background: rgba(255,255,255,0.06);
  color: #fff;
}
#sessions-dropdown .sess-item.active {
  color: #88aaff;
  background: rgba(100,140,255,0.1);
}
#sessions-dropdown .sess-item .time {
  font-size: 0.65rem;
  opacity: 0.4;
  margin-left: 0.4rem;
}
#sessions-dropdown .sess-item .save-label {
  display: block;
  font-size: 0.7rem;
  color: rgba(255,255,255,0.7);
  margin-bottom: 0.1rem;
}
#sessions-dropdown .sess-item .no-save {
  display: block;
  font-size: 0.65rem;
  color: rgba(255,255,255,0.2);
  font-style: italic;
  margin-bottom: 0.1rem;
}
#sessions-dropdown .sess-item .file-label {
  font-size: 0.65rem;
  opacity: 0.4;
}
#sessions-dropdown .no-sessions {
  padding: 0.4rem 0.8rem;
  color: rgba(255,255,255,0.15);
  font-size: 0.7rem;
  font-style: italic;
}
#sessions-dropdown .list::-webkit-scrollbar { width: 4px; }
#sessions-dropdown .list::-webkit-scrollbar-track { background: transparent; }
#sessions-dropdown .list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

#dropzone {
  border: 2px dashed rgba(255,255,255,0.3);
  border-radius: 8px;
  padding: 3rem 4rem;
  cursor: pointer;
  transition: border-color 0.2s, background 0.2s;
}
#dropzone:hover { border-color: rgba(255,255,255,0.6); background: rgba(255,255,255,0.05); }
#dropzone h2 { font-size: 1.5rem; font-weight: 300; margin-bottom: 2.5rem; letter-spacing: 1px; }
#dropzone .sub { font-size: 0.9rem; opacity: 0.5; margin-bottom: 1.5rem; }
#dropzone .hint { font-size: 0.75rem; opacity: 0.3; }
#dropzone.dragover { border-color: #88aaff; background: rgba(136,170,255,0.1); }

#instructions { display: none; }
#instructions.show { display: block; }
#instructions h1 { font-size: 2rem; font-weight: 300; margin-bottom: 0.5rem; letter-spacing: 1px; }
#instructions .sub { font-size: 1rem; opacity: 0.7; margin-bottom: 2rem; }
#instructions .btn {
  display: inline-block; padding: 0.8rem 2.5rem;
  border: 1px solid rgba(255,255,255,0.3); border-radius: 4px;
  font-size: 1rem; color: #fff; text-transform: uppercase; letter-spacing: 2px;
  transition: background 0.2s;
  cursor: pointer;
}
#instructions .btn:hover { background: rgba(255,255,255,0.1); }
#instructions .keys {
  margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; flex-direction: column;
  font-size: 0.8rem; opacity: 0.5;
}
#instructions .keys kbd {
  display: inline-block; padding: 0.2rem 0.6rem;
  border: 1px solid rgba(255,255,255,0.2); border-radius: 3px;
  font-family: inherit; font-size: 0.75rem;
}

#loading {
  position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%);
  display: flex; align-items: center; gap: 0.75rem;
  color: rgba(255,255,255,0.4); font-size: 0.85rem; letter-spacing: 1px;
  transition: opacity 0.4s;
}
#loading.hidden { opacity: 0; pointer-events: none; }

#loading .spinner {
  width: 14px; height: 14px;
  border: 2px solid rgba(255,255,255,0.15);
  border-top-color: rgba(255,255,255,0.6);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

#stats {
  position: fixed; bottom: 1rem; left: 1rem;
  color: rgba(255,255,255,0.4); font-size: 0.75rem; font-family: monospace;
  pointer-events: none; z-index: 5;
  transition: opacity 0.3s;
}

#saveBtn { display: none; }
#saveBtn.show { display: inline-block; }

#crosshair {
  position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
  z-index: 20; pointer-events: none;
  color: rgba(255,255,255,0.5); font-size: 1.5rem;
  line-height: 1; user-select: none;
}

#hud-keys {
  position: fixed; bottom: 3rem; right: 2rem; z-index: 20;
  color: rgba(255,255,255,0.2); font-size: 0.7rem;
  font-family: monospace; text-align: right; line-height: 1.6;
  pointer-events: none; user-select: none;
  transition: opacity 0.4s;
}
#textureModal { display: none; position: fixed; inset: 0; z-index: 50; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
#textureModal .panel { background: rgba(20,20,35,0.95); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 1.5rem; min-width: 360px; max-width: 560px; max-height: 80vh; overflow-y: auto; text-align: center; }
#textureModal h3 { color: #fff; font-weight: 300; letter-spacing: 1px; margin: 0 0 1rem 0; font-size: 1rem; }
#textureGrid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 1rem; }
#textureGrid .thumb { cursor: pointer; border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden; transition: border-color 0.2s; }
#textureGrid .thumb:hover { border-color: rgba(100,140,255,0.5); }
#textureGrid .thumb img { width: 100%; height: 80px; object-fit: cover; display: block; }
#textureGrid .thumb .label { padding: 0.25rem; font-size: 0.65rem; color: rgba(255,255,255,0.6); }
</style>
</head>
