<?php

namespace Database\Seeders;

use App\Models\Instruction;
use Illuminate\Database\Seeder;

class InstructionSeeder extends Seeder
{
    public function run(): void
    {
        $instructions = [
            [
                'title' => 'Getting Started — Uploading a 3D Model',
                'content' => '<p>When you first launch the app, you\'ll see the upload screen. Drag & drop your <strong>.obj</strong> and <strong>.mtl</strong> files onto the drop zone, or click to browse. You can also upload a <strong>.png</strong> texture image alongside your model.</p><p>Once uploaded, the model will appear in the scene. Click the <strong>"Click to enter"</strong> prompt to lock your cursor and begin exploring.</p>',
                'sort_order' => 1,
                'active' => true,
            ],
            [
                'title' => 'Navigation — Walking Around the Scene',
                'content' => '<p>After clicking to enter, use the following controls to move around:</p><ul><li><strong>WASD</strong> — Move forward, left, back, right</li><li><strong>E / Q</strong> — Move up / down (fly mode)</li><li><strong>Shift (hold)</strong> — Sprint</li><li><strong>Mouse</strong> — Look around (pointer must be locked)</li><li><strong>Esc</strong> — Release cursor</li></ul><p>The crosshair in the center helps you aim at objects.</p>',
                'sort_order' => 2,
                'active' => true,
            ],
            [
                'title' => 'Selecting & Transforming Objects',
                'content' => '<p>To manipulate an object in the scene, first select it by pressing the <strong>Select key</strong> (default: <kbd>L</kbd>) while looking at it. A blue outline will appear around the selected object.</p><p>Once selected, change the transform mode:</p><ul><li><strong>G</strong> — Translate (move) the object</li><li><strong>R</strong> — Rotate the object</li><li><strong>T</strong> — Scale the object</li></ul><p>Drag the colored arrows/rings/handles to transform the object in real time.</p>',
                'sort_order' => 3,
                'active' => true,
            ],
            [
                'title' => 'Recoloring Objects',
                'content' => '<p>Looking at an object, press the <strong>Recolor key</strong> (default: <kbd>K</kbd>) to cycle through available color/material presets. Each press applies the next manipulation style from the admin-defined list.</p><p>Presets can include solid colors, metallic finishes, glass-like transparency, and wireframe styles. The admin can configure these in the Manipulations panel.</p>',
                'sort_order' => 4,
                'active' => true,
            ],
            [
                'title' => 'Duplicating & Deleting Objects',
                'content' => '<p>Two ways to manipulate objects:</p><ul><li><strong>Duplicate (default: <kbd>H</kbd>)</strong> — Creates a copy of the selected or hovered object, offset slightly from the original. Each duplicate has its own independent materials and transformations.</li><li><strong>Delete (default: <kbd>Del</kbd> or <kbd>Backspace</kbd>)</strong> — Permanently removes the selected or hovered object from the scene.</li></ul><p>Use <strong>Undo (default: <kbd>Z</kbd>)</strong> to revert the last action.</p>',
                'sort_order' => 5,
                'active' => true,
            ],
            [
                'title' => 'Placing New Objects',
                'content' => '<p>To add a new object to your scene:</p><ol><li>Press the <strong>Place key</strong> (default: <kbd>O</kbd>) to open the file picker.</li><li>Select an <strong>.obj</strong> file (with optional <strong>.mtl</strong> and <strong>.png</strong>).</li><li>The model will appear at your current viewpoint.</li></ol><p>You can then select, move, resize, and recolor the placed object just like any other.</p><p><strong>Note:</strong> Your current plan limits how many objects you can have per scene.</p>',
                'sort_order' => 6,
                'active' => true,
            ],
            [
                'title' => 'Resize Mode & Texture Manager',
                'content' => '<p><strong>Resize Mode</strong> — Press <kbd>L</kbd> to toggle resize mode. While active, press <kbd>+</kbd> or <kbd>-</kbd> to scale the selected object up or down. Press <kbd>L</kbd> again to exit.</p><p><strong>Textures</strong> — Looking at an object, press <kbd>B</kbd> to open the texture selector. Choose from globally available textures uploaded by the admin. The texture is applied directly to the object\'s surface.</p><p><strong>Inspecting &amp; Adjusting Textures</strong> — Press <kbd>I</kbd> to inspect the object\'s size and current texture repeat value. While looking at a textured object, press <kbd>&gt;</kbd> to increase the texture repeat or <kbd>&lt;</kbd> to decrease it. The HUD displays the current value.</p>',
                'sort_order' => 7,
                'active' => true,
            ],
            [
                'title' => 'Sessions — Saving & Loading Your Work',
                'content' => '<p>The top-left <strong>Sessions</strong> dropdown lets you manage your saved scenes:</p><ul><li><strong>Save</strong> — Press <kbd>Ctrl+S</kbd> or double-tap <kbd>Esc</kbd> to open the save dialog. Enter a name and confirm.</li><li><strong>Load</strong> — Click any saved session from the dropdown list to load it. The scene will be restored with all objects, positions, and colors.</li><li>Each session tracks its objects, transformations, colors, and textures.</li></ul><p>Sessions are organized by upload date and show the OBJ files they contain.</p>',
                'sort_order' => 8,
                'active' => true,
            ],
            [
                'title' => 'Understanding Plan Limits',
                'content' => '<p>Your account has a plan that determines usage limits:</p><ul><li><strong>Free Plan</strong> — 5 sessions, 5 objects per scene</li><li><strong>Pro Plan</strong> — 50 sessions, 50 objects per scene ($9.99/month)</li><li><strong>Expert Plan</strong> — Unlimited sessions and objects ($29.99/lifetime)</li></ul><p>When you reach a limit, a modal will appear offering upgrade options. You can submit an upgrade request from the modal or from your Dashboard. An admin will review and approve it.</p><p>If your plan expires, you\'ll see a renewal prompt but can continue using it.</p>',
                'sort_order' => 9,
                'active' => true,
            ],
            [
                'title' => 'Light Controls',
                'content' => '<p>Adjust the scene lighting in real time using <kbd>Alt</kbd> + arrow keys:</p><ul><li><strong><kbd>Alt</kbd> + <kbd>←</kbd> / <kbd>→</kbd></strong> — Rotate the directional light around the scene (left / right)</li><li><strong><kbd>Alt</kbd> + <kbd>↑</kbd></strong> — Raise the light elevation (up to 25 units)</li><li><strong><kbd>Alt</kbd> + <kbd>↓</kbd></strong> — Lower the light elevation (down to 0.5 units)</li></ul><p>The HUD displays the current light position as you adjust it. Lighting changes are real-time and help you inspect your model from different angles.</p>',
                'sort_order' => 11,
                'active' => true,
            ],
            [
                'title' => 'Keyboard Shortcuts Reference',
                'content' => '<p>Here\'s a complete reference of all keyboard shortcuts:</p><table style="width:100%;border-collapse:collapse;font-size:0.9rem;"><thead><tr style="border-bottom:1px solid rgba(255,255,255,0.1);"><th style="text-align:left;padding:0.5rem 0.75rem;color:#f1f5f9;">Key</th><th style="text-align:left;padding:0.5rem 0.75rem;color:#f1f5f9;">Action</th></tr></thead><tbody><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>W</kbd> <kbd>A</kbd> <kbd>S</kbd> <kbd>D</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Move forward / left / back / right</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>E</kbd> / <kbd>Q</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Move up / down</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>Shift</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Sprint</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>K</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Recolor hovered object</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>H</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Duplicate hovered object</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>Del</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Delete selected/hovered object</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>Z</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Undo last action</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>L</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Select object / toggle resize mode</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>O</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Place object (open file picker)</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>G</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Translate mode</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>R</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Rotate mode</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>T</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Scale mode</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>+</kbd> / <kbd>-</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Resize selected object (in resize mode)</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>B</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Open texture selector</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>I</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Inspect object size &amp; texture repeat</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>&gt;</kbd> / <kbd>&lt;</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Increase / decrease texture repeat on hovered object</td></tr><tr style="border-bottom:1px solid rgba(255,255,255,0.05);"><td style="padding:0.5rem 0.75rem;"><kbd>Ctrl+S</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Save scene</td></tr><tr><td style="padding:0.5rem 0.75rem;"><kbd>Alt</kbd> + <kbd>←</kbd><kbd>→</kbd><kbd>↑</kbd><kbd>↓</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Rotate light / change elevation</td></tr><tr><td style="padding:0.5rem 0.75rem;"><kbd>Esc</kbd></td><td style="padding:0.5rem 0.75rem;color:#94a3b8;">Release cursor / close dialogs</td></tr></tbody></table>',
                'sort_order' => 10,
                'active' => true,
            ],
        ];

        foreach ($instructions as $data) {
            Instruction::create($data);
        }

        $this->command->info('Seeded ' . count($instructions) . ' instructions.');
    }
}

