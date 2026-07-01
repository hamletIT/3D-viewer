fetchSessions();

// Check if a session was specified in the URL
const urlParams = new URLSearchParams(window.location.search);
const sessionParam = urlParams.get('session');
if (sessionParam) {
  const waitForSession = setInterval(() => {
    if (document.querySelector('.sess-item')) {
      clearInterval(waitForSession);
      loadSessionFiles(sessionParam);
    }
  }, 100);
}

// Track changes — save only on explicit Ctrl+S or double Esc
transformControls.addEventListener('objectChange', () => { markDirty(); });

// Animation loop
function animate() {
  const delta = Math.min(clock.getDelta(), 0.05);
  const speed = moveSpeed * (keys.sprint ? sprintMultiplier : 1);

  // Movement
  if (controls.isLocked) {
    direction.set(0, 0, 0);
    if (keys.forward) direction.z -= 1;
    if (keys.backward) direction.z += 1;
    if (keys.left) direction.x -= 1;
    if (keys.right) direction.x += 1;
    direction.normalize();

    _movForward.set(0, 0, -1).applyQuaternion(camera.quaternion);
    _movRight.set(1, 0, 0).applyQuaternion(camera.quaternion);
    _movForward.y = 0; _movForward.normalize();
    _movRight.y = 0; _movRight.normalize();

    velocity.x += (direction.x * _movRight.x + direction.z * _movForward.x) * speed * delta - velocity.x * 10 * delta;
    velocity.z += (direction.x * _movRight.z + direction.z * _movForward.z) * speed * delta - velocity.z * 10 * delta;

    let vertical = 0;
    if (keys.up) vertical += 1;
    if (keys.down) vertical -= 1;
    velocity.y += vertical * speed * delta - velocity.y * 10 * delta;

    camera.position.x += velocity.x * delta;
    camera.position.z += velocity.z * delta;
    camera.position.y += velocity.y * delta;

    if (statsEl && modelLoaded && !statsLocked) {
      statsEl.textContent = `pos: ${camera.position.x.toFixed(2)}, ${camera.position.y.toFixed(2)}, ${camera.position.z.toFixed(2)}`;
      statsEl.style.opacity = '0.5';
    }
  }

  renderer.render(scene, camera);
  requestAnimationFrame(animate);
}

animate();
