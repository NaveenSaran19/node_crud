<?php
// Attribute Settings View Partial
$rawColors = $settings['attribute_colors'] ?? '';
$colorsList = !empty($rawColors) ? json_decode($rawColors, true) : null;
if (!is_array($colorsList) || empty($colorsList)) {
    $colorsList = [
        ['name' => 'Red', 'code' => '#dc2626', 'dark' => 1],
        ['name' => 'Maroon', 'code' => '#800020', 'dark' => 1],
        ['name' => 'Pink', 'code' => '#ec4899', 'dark' => 1],
        ['name' => 'Blue', 'code' => '#2563eb', 'dark' => 1],
        ['name' => 'Navy', 'code' => '#1e3a8a', 'dark' => 1],
        ['name' => 'Green', 'code' => '#16a34a', 'dark' => 1],
        ['name' => 'Teal', 'code' => '#0d9488', 'dark' => 1],
        ['name' => 'Yellow', 'code' => '#eab308', 'dark' => 0],
        ['name' => 'Orange', 'code' => '#ea580c', 'dark' => 1],
        ['name' => 'Gold', 'code' => '#d97706', 'dark' => 1],
        ['name' => 'Purple', 'code' => '#9333ea', 'dark' => 1],
        ['name' => 'Lavender', 'code' => '#c084fc', 'dark' => 0],
        ['name' => 'Black', 'code' => '#18181b', 'dark' => 1],
        ['name' => 'Cream', 'code' => '#fef08a', 'dark' => 0],
        ['name' => 'White', 'code' => '#ffffff', 'dark' => 0],
    ];
}

$rawSizes = $settings['attribute_sizes'] ?? '';
$sizesList = !empty($rawSizes) ? json_decode($rawSizes, true) : null;
if (!is_array($sizesList) || empty($sizesList)) {
    $sizesList = [
        ['name' => '0-3 Months', 'category' => 'Kids', 'order' => 1],
        ['name' => '3-6 Months', 'category' => 'Kids', 'order' => 2],
        ['name' => '6-12 Months', 'category' => 'Kids', 'order' => 3],
        ['name' => '1-2 Yrs', 'category' => 'Kids', 'order' => 4],
        ['name' => '2-3 Yrs', 'category' => 'Kids', 'order' => 5],
        ['name' => '3-4 Yrs', 'category' => 'Kids', 'order' => 6],
        ['name' => '4-5 Yrs', 'category' => 'Kids', 'order' => 7],
        ['name' => '5-6 Yrs', 'category' => 'Kids', 'order' => 8],
        ['name' => '6-7 Yrs', 'category' => 'Kids', 'order' => 9],
        ['name' => '7-8 Yrs', 'category' => 'Kids', 'order' => 10],
        ['name' => '8-9 Yrs', 'category' => 'Kids', 'order' => 11],
        ['name' => '9-10 Yrs', 'category' => 'Kids', 'order' => 12],
        ['name' => '10-12 Yrs', 'category' => 'Kids', 'order' => 13],
        ['name' => '12-14 Yrs', 'category' => 'Kids', 'order' => 14],
        ['name' => 'Free Size', 'category' => 'General', 'order' => 15],
        ['name' => 'S', 'category' => 'Apparel', 'order' => 16],
        ['name' => 'M', 'category' => 'Apparel', 'order' => 17],
        ['name' => 'L', 'category' => 'Apparel', 'order' => 18],
        ['name' => 'XL', 'category' => 'Apparel', 'order' => 19],
        ['name' => 'XXL', 'category' => 'Apparel', 'order' => 20],
        ['name' => '3XL', 'category' => 'Apparel', 'order' => 21],
        ['name' => '4 Muzham (Single Dhoti)', 'category' => 'Dhoti', 'order' => 22],
        ['name' => '8 Muzham (Double Dhoti)', 'category' => 'Dhoti', 'order' => 23],
        ['name' => '9x5 (Dhoti + Angavastram Set)', 'category' => 'Dhoti', 'order' => 24],
        ['name' => '10x6 (Pattu Dhoti + Shawl Set)', 'category' => 'Dhoti', 'order' => 25],
        ['name' => '2.0 Meters (Single Dhoti)', 'category' => 'Dhoti', 'order' => 26],
        ['name' => '4.0 Meters (Double Dhoti)', 'category' => 'Dhoti', 'order' => 27],
        ['name' => 'Readymade / Pocket Dhoti', 'category' => 'Dhoti', 'order' => 28],
    ];
}
?>

<div id="tab-attributes" class="settings-section <?= ($currentSection === 'attributes') ? 'active' : '' ?>">
    <form action="<?= base_url('admin/settings/update') ?>" method="POST" class="settings-section-form" id="attributeSettingsForm">
        <input type="hidden" name="section_name" value="attributes">
        <input type="hidden" name="active_tab_redirect" value="attributes">

        <!-- Top Header Banner -->
        <div style="margin-bottom: 22px; padding-bottom: 18px; border-bottom: 1.5px solid #f1f5f9; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
            <div>
                <h3 style="font-size: 19px; font-weight: 800; color: #1e293b; margin: 0 0 6px; display: flex; align-items: center; gap: 9px;">
                    <i class="fa-solid fa-palette" style="color: #e11d48;"></i> Color & Size Attribute Manager
                </h3>
                <p style="font-size: 13.5px; color: #64748b; margin: 0; max-width: 720px;">
                    Create and manage standard <strong>Color</strong> palettes and <strong>Size / Dhoti</strong> dimensions. These attributes automatically populate the Product Add/Edit variant dropdowns, datalists, and the customer storefront sidebar filters.
                </p>
            </div>
            <button type="submit" class="btn-section-save" style="padding: 10px 22px; font-size: 13.5px;">
                <i class="fa-solid fa-floppy-disk"></i> Save Attribute Settings
            </button>
        </div>

        <!-- Interactive Sliding Switch between 1. Color Attributes and 2. Size & Dimension Attributes -->
        <div class="attr-swipe-switch-wrapper" style="margin-bottom: 25px; background: #fffdfa; border: 1.5px solid #f1e5d1; border-radius: 14px; padding: 18px 22px; box-shadow: 0 4px 15px rgba(225, 29, 72,0.03); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 10px; background: linear-gradient(135deg, #e11d48, #a11c40); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 18px; box-shadow: 0 4px 10px rgba(225, 29, 72,0.25);">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <div style="font-size: 14px; font-weight: 800; color: #1e293b; letter-spacing: 0.3px;">ATTRIBUTE TYPE SWITCHER</div>
                    <div style="font-size: 12px; color: #64748b;">Slide or tap to switch between Color Attributes and Size &amp; Dimension Attributes</div>
                </div>
            </div>

            <!-- Swipe Switch Capsule -->
            <div class="attr-swipe-switch" id="attrSwipeSwitch" role="group" aria-label="Attribute Switcher" style="position: relative; display: inline-flex; align-items: center; background: #eef2f6; padding: 4px; border-radius: 40px; border: 1px solid #cbd5e1; user-select: none; box-shadow: inset 0 2px 4px rgba(0,0,0,0.04); isolation: isolate;">
                <div class="attr-switch-glider" id="attrSwitchGlider" style="position: absolute; top: 4px; left: 0; height: calc(100% - 8px); width: 0; background: linear-gradient(135deg, #e11d48 0%, #4a0818 100%); border-radius: 35px; box-shadow: 0 4px 12px rgba(225, 29, 72,0.32); transition: transform 0.32s cubic-bezier(0.25, 1, 0.5, 1), width 0.32s cubic-bezier(0.25, 1, 0.5, 1), background 0.3s ease, box-shadow 0.3s ease; z-index: 1; pointer-events: none; opacity: 0;"></div>
                
                <button type="button" class="attr-switch-btn active" id="attrBtnColor" onclick="switchAttributeTab('color')" style="position: relative; z-index: 2; display: inline-flex; align-items: center; gap: 8px; padding: 9px 20px; border-radius: 35px; border: none; background: transparent; color: #ffffff; font-size: 13.5px; font-weight: 700; cursor: pointer; transition: color 0.25s ease, transform 0.15s ease; outline: none;">
                    <i class="fa-solid fa-droplet" style="font-size: 14px;"></i>
                    <span>1. Color Attributes</span>
                    <span class="attr-pill-badge" id="pillBadgeColor" style="background: rgba(255,255,255,0.22); color: #ffffff; padding: 1px 7px; border-radius: 12px; font-size: 11px; font-weight: 800;"><?= count($colorsList) ?></span>
                </button>

                <button type="button" class="attr-switch-btn" id="attrBtnSize" onclick="switchAttributeTab('size')" style="position: relative; z-index: 2; display: inline-flex; align-items: center; gap: 8px; padding: 9px 20px; border-radius: 35px; border: none; background: transparent; color: #64748b; font-size: 13.5px; font-weight: 700; cursor: pointer; transition: color 0.25s ease, transform 0.15s ease; outline: none;">
                    <i class="fa-solid fa-ruler-horizontal" style="font-size: 14px;"></i>
                    <span>2. Size &amp; Dimension Attributes</span>
                    <span class="attr-pill-badge" id="pillBadgeSize" style="background: rgba(0,0,0,0.08); color: #475569; padding: 1px 7px; border-radius: 12px; font-size: 11px; font-weight: 800;"><?= count($sizesList) ?></span>
                </button>
            </div>
        </div>

        <!-- 1. COLOR ATTRIBUTES SECTION (Container for Tab 1) -->
        <div id="attr-pane-color" class="attr-pane" style="display: block; animation: attrFadeIn 0.3s ease;">
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 24px; margin-bottom: 25px; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0 0 4px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-droplet" style="color: #e11d48;"></i> 1. Color Attributes (<span id="colorCountBadge"><?= count($colorsList) ?></span>)
                        </h4>
                        <p style="font-size: 12.5px; color: #64748b; margin: 0;">Specify Color Name, HEX color picker, and check if swatch needs white/light checkmark.</p>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="btn-sm" onclick="addColorAttributeRow()" style="background: #e11d48; color: #fff; border: none; border-radius: 8px; padding: 7px 14px; font-size: 12.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-plus"></i> Add Color
                        </button>
                    </div>
                </div>

                <div class="table-responsive-wrapper" style="overflow-x: auto;">
                    <table class="variant-table" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr>
                                <th style="width: 70px; text-align: center;">Preview</th>
                                <th style="width: 90px; text-align: center;">HEX Picker</th>
                                <th style="min-width: 180px;">Color Name (e.g. Maroon, Rani Pink)</th>
                                <th style="width: 130px; text-align: center;">HEX Code</th>
                                <th style="width: 160px; text-align: center;">Text / Icon Contrast</th>
                                <th style="width: 80px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="attributeColorsTableBody">
                            <?php foreach($colorsList as $idx => $c): 
                                $cHex = !empty($c['code']) ? $c['code'] : '#e11d48';
                                $cName = $c['name'] ?? '';
                                $isDark = !empty($c['dark']);
                            ?>
                            <tr class="attr-color-row" data-index="<?= $idx ?>">
                                <td style="text-align: center;">
                                    <div class="color-preview-circle" style="width: 32px; height: 32px; border-radius: 50%; background-color: <?= esc($cHex) ?>; border: 1.5px solid #cbd5e1; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        <i class="fa-solid fa-check" style="font-size: 12px; color: <?= $isDark ? '#ffffff' : '#1e293b' ?>;"></i>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <input type="color" name="attribute_colors[<?= $idx ?>][code]" value="<?= esc($cHex) ?>" class="attr-color-picker" onchange="syncColorPickerRow(this)" style="width: 40px; height: 36px; padding: 0; border: 1.5px solid #cbd5e1; border-radius: 8px; cursor: pointer; background: none;">
                                </td>
                                <td>
                                    <input type="text" name="attribute_colors[<?= $idx ?>][name]" value="<?= esc($cName) ?>" placeholder="Color name..." class="premium-input attr-color-name" required style="padding: 8px 12px; font-weight: 600; font-size: 13px;">
                                </td>
                                <td style="text-align: center;">
                                    <input type="text" value="<?= esc($cHex) ?>" class="premium-input attr-hex-text" oninput="syncHexTextInput(this)" placeholder="#000000" style="padding: 8px 10px; font-family: monospace; font-size: 12px; text-align: center; text-transform: uppercase;">
                                </td>
                                <td style="text-align: center;">
                                    <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; cursor: pointer; user-select: none;">
                                        <input type="checkbox" name="attribute_colors[<?= $idx ?>][dark]" value="1" <?= $isDark ? 'checked' : '' ?> onchange="syncColorContrast(this)">
                                        <span style="font-weight: 600; color: #475569;">Dark background (White icon)</span>
                                    </label>
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn-sm btn-outline-danger" onclick="removeAttributeRow(this)" title="Remove color" style="background: none; border: 1px solid #fee2e2; color: #dc2626; border-radius: 6px; width: 32px; height: 32px; cursor: pointer;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 14px;">
                    <button type="button" onclick="addColorAttributeRow()" style="background: #f8fafc; border: 1.5px dashed #cbd5e1; color: #e11d48; font-weight: 700; font-size: 13px; padding: 10px 18px; border-radius: 8px; cursor: pointer; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;">
                        <i class="fa-solid fa-circle-plus"></i> Add Another Color Attribute
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. SIZE / MEASUREMENT ATTRIBUTES SECTION (Container for Tab 2) -->
        <div id="attr-pane-size" class="attr-pane" style="display: none; animation: attrFadeIn 0.3s ease;">
            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 24px; margin-bottom: 25px; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0 0 4px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-ruler-horizontal" style="color: #2563eb;"></i> 2. Size &amp; Dimension Attributes (<span id="sizeCountBadge"><?= count($sizesList) ?></span>)
                        </h4>
                        <p style="font-size: 12.5px; color: #64748b; margin: 0;">Define clothing sizes (S, M, L, XL), Dhoti dimensions (4 Muzham, 8 Muzham), or Free Size options.</p>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="btn-sm" onclick="addSizeAttributeRow()" style="background: #2563eb; color: #fff; border: none; border-radius: 8px; padding: 7px 14px; font-size: 12.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-plus"></i> Add Size / Dimension
                        </button>
                    </div>
                </div>

                <div class="table-responsive-wrapper" style="overflow-x: auto;">
                    <table class="variant-table" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">#</th>
                                <th style="min-width: 220px;">Size / Dimension Name (e.g. Free Size, 4 Muzham, XL)</th>
                                <th style="width: 200px;">Category Group</th>
                                <th style="width: 100px; text-align: center;">Display Order</th>
                                <th style="width: 80px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="attributeSizesTableBody">
                            <?php foreach($sizesList as $sIdx => $s): 
                                $sName = $s['name'] ?? '';
                                $sCat = $s['category'] ?? 'General';
                                $sOrd = $s['order'] ?? ($sIdx + 1);
                            ?>
                            <tr class="attr-size-row" data-index="<?= $sIdx ?>">
                                <td style="text-align: center; color: #94a3b8; font-weight: 700; font-size: 12px;">
                                    <?= $sIdx + 1 ?>
                                </td>
                                <td>
                                    <input type="text" name="attribute_sizes[<?= $sIdx ?>][name]" value="<?= esc($sName) ?>" placeholder="Size name..." class="premium-input attr-size-name" required style="padding: 8px 12px; font-weight: 600; font-size: 13px;">
                                </td>
                                <td>
                                    <select name="attribute_sizes[<?= $sIdx ?>][category]" class="premium-input attr-size-category" style="padding: 8px 12px; font-size: 13px;">
                                        <option value="General" <?= $sCat === 'General' ? 'selected' : '' ?>>General (Free Size)</option>
                                        <option value="Apparel" <?= $sCat === 'Apparel' ? 'selected' : '' ?>>Apparel (S, M, L, XL, etc.)</option>
                                        <option value="Dhoti" <?= $sCat === 'Dhoti' ? 'selected' : '' ?>>Dhoti &amp; Sets (4M, 8M, 9x5)</option>
                                        <option value="Saree" <?= $sCat === 'Saree' ? 'selected' : '' ?>>Saree Dimensions</option>
                                    </select>
                                </td>
                                <td style="text-align: center;">
                                    <input type="number" name="attribute_sizes[<?= $sIdx ?>][order]" value="<?= esc($sOrd) ?>" min="0" class="premium-input" style="padding: 8px 8px; text-align: center; font-size: 13px;">
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn-sm btn-outline-danger" onclick="removeAttributeRow(this)" title="Remove size" style="background: none; border: 1px solid #fee2e2; color: #dc2626; border-radius: 6px; width: 32px; height: 32px; cursor: pointer;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 14px;">
                    <button type="button" onclick="addSizeAttributeRow()" style="background: #f8fafc; border: 1.5px dashed #cbd5e1; color: #2563eb; font-weight: 700; font-size: 13px; padding: 10px 18px; border-radius: 8px; cursor: pointer; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;">
                        <i class="fa-solid fa-circle-plus"></i> Add Another Size Attribute
                    </button>
                </div>
            </div>
        </div>

        <!-- === LIVE ATTRIBUTE DISPLAY PREVIEW PANEL === -->
        <div id="attr-live-preview-panel" style="margin-top: 28px; background: linear-gradient(135deg, #fdfcfb 0%, #fff9f5 100%); border: 1.5px solid #f1e5d1; border-radius: 16px; padding: 24px 26px; box-shadow: 0 6px 24px rgba(225, 29, 72,0.05);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #e11d48, #a11c40); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 15px; box-shadow: 0 3px 8px rgba(225, 29, 72,0.25);">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 800; color: #1e293b; letter-spacing: 0.2px;">Live Attribute Preview</div>
                        <div style="font-size: 12px; color: #64748b;">How your colors & sizes appear on storefront and variant forms</div>
                    </div>
                </div>
                <span style="font-size: 11.5px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 8px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fa-solid fa-circle-check"></i> Auto-syncs on save
                </span>
            </div>

            <!-- Color Preview Strip -->
            <div style="margin-bottom: 22px;">
                <div style="font-size: 11.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #e11d48; margin-bottom: 12px; display: flex; align-items: center; gap: 7px;">
                    <i class="fa-solid fa-droplet"></i> Color Palette
                    <span style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 1px 7px; border-radius: 10px; font-size: 10.5px;"><?= count($colorsList) ?> Colors</span>
                </div>
                <div id="preview-color-swatches" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                    <?php foreach($colorsList as $ci => $cItem): 
                        $cItemHex = !empty($cItem['code']) ? $cItem['code'] : '#e11d48';
                        $cItemName = $cItem['name'] ?? '';
                        $cItemDark = !empty($cItem['dark']);
                    ?>
                    <div class="attr-preview-swatch" title="<?= esc($cItemName) ?>" style="display: flex; flex-direction: column; align-items: center; gap: 5px; cursor: default; position: relative;" data-color-idx="<?= $ci ?>">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background-color: <?= esc($cItemHex) ?>; border: 2.5px solid rgba(0,0,0,0.08); box-shadow: 0 3px 8px rgba(0,0,0,0.12), inset 0 1px 0 rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; transition: transform 0.2s ease;" onmouseover="this.style.transform='scale(1.12)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="fa-solid fa-check" style="font-size: 12px; color: <?= $cItemDark ? '#ffffff' : '#1e293b' ?>;"></i>
                        </div>
                        <span style="font-size: 10px; font-weight: 700; color: #475569; text-align: center; max-width: 52px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= esc($cItemName) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Divider -->
            <div style="border-top: 1px dashed #e2e8f0; margin-bottom: 22px;"></div>

            <!-- Size Chips Preview -->
            <div>
                <div style="font-size: 11.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #2563eb; margin-bottom: 12px; display: flex; align-items: center; gap: 7px;">
                    <i class="fa-solid fa-ruler-horizontal"></i> Size & Dimension Options
                    <span style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; padding: 1px 7px; border-radius: 10px; font-size: 10.5px;"><?= count($sizesList) ?> Options</span>
                </div>
                <div id="preview-size-chips" style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <?php 
                        $groupedSizes = [];
                        foreach($sizesList as $si => $sItem) {
                            $cat = !empty($sItem['category']) ? $sItem['category'] : 'General';
                            $groupedSizes[$cat][] = $sItem['name'] ?? '';
                        }
                        $catColors = [
                            'General' => ['bg' => '#f1f5f9', 'border' => '#cbd5e1', 'text' => '#475569'],
                            'Apparel' => ['bg' => '#fff7ed', 'border' => '#fed7aa', 'text' => '#9a3412'],
                            'Dhoti'   => ['bg' => '#f0fdf4', 'border' => '#a7f3d0', 'text' => '#065f46'],
                            'Saree'   => ['bg' => '#fdf4ff', 'border' => '#e9d5ff', 'text' => '#7e22ce'],
                        ];
                        foreach($groupedSizes as $cat => $sNames):
                            $catStyle = $catColors[$cat] ?? ['bg' => '#f1f5f9', 'border' => '#cbd5e1', 'text' => '#475569'];
                    ?>
                    <div style="display: flex; flex-wrap: wrap; gap: 6px; align-items: center; margin-bottom: 4px;">
                        <span style="font-size: 10px; font-weight: 800; color: <?= $catStyle['text'] ?>; text-transform: uppercase; letter-spacing: 0.5px; background: <?= $catStyle['bg'] ?>; border: 1px solid <?= $catStyle['border'] ?>; padding: 2px 7px; border-radius: 6px; white-space: nowrap;">
                            <?php 
                                $icons = ['General' => '🏷️', 'Apparel' => '👔', 'Dhoti' => '🥻', 'Saree' => '📐'];
                                echo ($icons[$cat] ?? '•') . ' ' . esc($cat);
                            ?>
                        </span>
                        <?php foreach($sNames as $sz): ?>
                        <span style="display: inline-flex; align-items: center; gap: 4px; background: <?= $catStyle['bg'] ?>; border: 1.5px solid <?= $catStyle['border'] ?>; color: <?= $catStyle['text'] ?>; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 8px; white-space: nowrap; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 3px 8px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                            <?= esc($sz) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- END LIVE PREVIEW PANEL -->

        <!-- Section Footer -->
        <div class="section-card-footer" style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; margin-top: 24px; border-top: 1.5px solid #f1f5f9;">
            <span class="section-footer-info" style="font-size: 13px; color: #16a34a; display: flex; align-items: center; gap: 7px; font-weight: 600;">
                <i class="fa-solid fa-circle-check"></i> Automatically reflected across Admin Product forms &amp; Storefront Filters
            </span>
            <button type="submit" class="btn-section-save" style="padding: 11px 26px; font-size: 14px;">
                <i class="fa-solid fa-floppy-disk"></i> Save Attribute Settings
            </button>
        </div>
    </form>
</div>

<style>
@keyframes attrFadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}
.attr-switch-btn:hover {
    color: #1e293b !important;
}
.attr-switch-btn.active:hover {
    color: #ffffff !important;
}
.attr-switch-btn:active {
    transform: scale(0.97);
}
</style>

<script>
var attrColorNextIdx = <?= count($colorsList) ?>;
var attrSizeNextIdx = <?= count($sizesList) ?>;
var currentAttrTab = 'color';

function updateAttrGlider(immediate = false) {
    var sw = document.getElementById('attrSwipeSwitch');
    var glider = document.getElementById('attrSwitchGlider');
    if (!sw || !glider) return;

    var activeBtn = sw.querySelector('.attr-switch-btn.active');
    if (!activeBtn) return;

    if (currentAttrTab === 'size') {
        glider.style.background = 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)';
        glider.style.boxShadow = '0 4px 14px rgba(37, 99, 235, 0.35)';
    } else {
        glider.style.background = 'linear-gradient(135deg, #e11d48 0%, #4a0818 100%)';
        glider.style.boxShadow = '0 4px 12px rgba(225, 29, 72, 0.32)';
    }

    if (immediate) {
        var prevTrans = glider.style.transition;
        glider.style.transition = 'none';
        glider.style.width = activeBtn.offsetWidth + 'px';
        glider.style.transform = 'translateX(' + activeBtn.offsetLeft + 'px)';
        glider.style.opacity = '1';
        void glider.offsetWidth;
        glider.style.transition = prevTrans;
    } else {
        glider.style.width = activeBtn.offsetWidth + 'px';
        glider.style.transform = 'translateX(' + activeBtn.offsetLeft + 'px)';
        glider.style.opacity = '1';
    }
}

function switchAttributeTab(tabKey) {
    currentAttrTab = tabKey;
    var btnColor = document.getElementById('attrBtnColor');
    var btnSize = document.getElementById('attrBtnSize');
    var paneColor = document.getElementById('attr-pane-color');
    var paneSize = document.getElementById('attr-pane-size');
    var pillColor = document.getElementById('pillBadgeColor');
    var pillSize = document.getElementById('pillBadgeSize');

    if (tabKey === 'size') {
        if (btnColor) {
            btnColor.classList.remove('active');
            btnColor.style.color = '#64748b';
            if (pillColor) { pillColor.style.background = 'rgba(0,0,0,0.08)'; pillColor.style.color = '#475569'; }
        }
        if (btnSize) {
            btnSize.classList.add('active');
            btnSize.style.color = '#ffffff';
            if (pillSize) { pillSize.style.background = 'rgba(255,255,255,0.22)'; pillSize.style.color = '#ffffff'; }
        }
        if (paneColor) paneColor.style.display = 'none';
        if (paneSize) paneSize.style.display = 'block';
    } else {
        if (btnSize) {
            btnSize.classList.remove('active');
            btnSize.style.color = '#64748b';
            if (pillSize) { pillSize.style.background = 'rgba(0,0,0,0.08)'; pillSize.style.color = '#475569'; }
        }
        if (btnColor) {
            btnColor.classList.add('active');
            btnColor.style.color = '#ffffff';
            if (pillColor) { pillColor.style.background = 'rgba(255,255,255,0.22)'; pillColor.style.color = '#ffffff'; }
        }
        if (paneSize) paneSize.style.display = 'none';
        if (paneColor) paneColor.style.display = 'block';
    }

    updateAttrGlider(false);
}

document.addEventListener('DOMContentLoaded', function() {
    updateAttrGlider(true);
});
window.addEventListener('load', function() { updateAttrGlider(true); });
window.addEventListener('resize', function() { updateAttrGlider(true); });

function addColorAttributeRow() {
    var tbody = document.getElementById('attributeColorsTableBody');
    var idx = attrColorNextIdx++;
    var defaultHex = '#e11d48';

    var tr = document.createElement('tr');
    tr.className = 'attr-color-row';
    tr.dataset.index = idx;
    tr.innerHTML = `
        <td style="text-align: center;">
            <div class="color-preview-circle" style="width: 32px; height: 32px; border-radius: 50%; background-color: ${defaultHex}; border: 1.5px solid #cbd5e1; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fa-solid fa-check" style="font-size: 12px; color: #ffffff;"></i>
            </div>
        </td>
        <td style="text-align: center;">
            <input type="color" name="attribute_colors[${idx}][code]" value="${defaultHex}" class="attr-color-picker" onchange="syncColorPickerRow(this)" style="width: 40px; height: 36px; padding: 0; border: 1.5px solid #cbd5e1; border-radius: 8px; cursor: pointer; background: none;">
        </td>
        <td>
            <input type="text" name="attribute_colors[${idx}][name]" value="" placeholder="Color name (e.g. Royal Blue)..." class="premium-input attr-color-name" required style="padding: 8px 12px; font-weight: 600; font-size: 13px;">
        </td>
        <td style="text-align: center;">
            <input type="text" value="${defaultHex}" class="premium-input attr-hex-text" oninput="syncHexTextInput(this)" placeholder="#000000" style="padding: 8px 10px; font-family: monospace; font-size: 12px; text-align: center; text-transform: uppercase;">
        </td>
        <td style="text-align: center;">
            <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; cursor: pointer; user-select: none;">
                <input type="checkbox" name="attribute_colors[${idx}][dark]" value="1" checked onchange="syncColorContrast(this)">
                <span style="font-weight: 600; color: #475569;">Dark background (White icon)</span>
            </label>
        </td>
        <td style="text-align: center;">
            <button type="button" class="btn-sm btn-outline-danger" onclick="removeAttributeRow(this)" title="Remove color" style="background: none; border: 1px solid #fee2e2; color: #dc2626; border-radius: 6px; width: 32px; height: 32px; cursor: pointer;">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    updateColorCount();
    var nameInput = tr.querySelector('.attr-color-name');
    if (nameInput) nameInput.focus();
}

function addSizeAttributeRow() {
    var tbody = document.getElementById('attributeSizesTableBody');
    var idx = attrSizeNextIdx++;
    var rowCount = tbody.querySelectorAll('.attr-size-row').length + 1;

    var tr = document.createElement('tr');
    tr.className = 'attr-size-row';
    tr.dataset.index = idx;
    tr.innerHTML = `
        <td style="text-align: center; color: #94a3b8; font-weight: 700; font-size: 12px;">
            ${rowCount}
        </td>
        <td>
            <input type="text" name="attribute_sizes[${idx}][name]" value="" placeholder="Size name (e.g. 40 (M), 8 Muzham)..." class="premium-input attr-size-name" required style="padding: 8px 12px; font-weight: 600; font-size: 13px;">
        </td>
        <td>
            <select name="attribute_sizes[${idx}][category]" class="premium-input attr-size-category" style="padding: 8px 12px; font-size: 13px;">
                <option value="General">General (Free Size)</option>
                <option value="Apparel" selected>Apparel (S, M, L, XL, etc.)</option>
                <option value="Dhoti">Dhoti &amp; Sets (4M, 8M, 9x5)</option>
                <option value="Saree">Saree Dimensions</option>
            </select>
        </td>
        <td style="text-align: center;">
            <input type="number" name="attribute_sizes[${idx}][order]" value="${rowCount}" min="0" class="premium-input" style="padding: 8px 8px; text-align: center; font-size: 13px;">
        </td>
        <td style="text-align: center;">
            <button type="button" class="btn-sm btn-outline-danger" onclick="removeAttributeRow(this)" title="Remove size" style="background: none; border: 1px solid #fee2e2; color: #dc2626; border-radius: 6px; width: 32px; height: 32px; cursor: pointer;">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    updateSizeCount();
    var nameInput = tr.querySelector('.attr-size-name');
    if (nameInput) nameInput.focus();
}

function removeAttributeRow(btn) {
    var tr = btn.closest('tr');
    if (tr) {
        tr.remove();
        updateColorCount();
        updateSizeCount();
    }
}

function syncColorPickerRow(picker) {
    var tr = picker.closest('tr');
    if (!tr) return;
    var hex = picker.value;
    var hexText = tr.querySelector('.attr-hex-text');
    var preview = tr.querySelector('.color-preview-circle');
    if (hexText) hexText.value = hex;
    if (preview) preview.style.backgroundColor = hex;
}

function syncHexTextInput(input) {
    var hex = input.value.trim();
    if (/^#[0-9A-Fa-f]{6}$/.test(hex)) {
        var tr = input.closest('tr');
        if (!tr) return;
        var picker = tr.querySelector('.attr-color-picker');
        var preview = tr.querySelector('.color-preview-circle');
        if (picker) picker.value = hex;
        if (preview) preview.style.backgroundColor = hex;
    }
}

function syncColorContrast(cb) {
    var tr = cb.closest('tr');
    if (!tr) return;
    var icon = tr.querySelector('.color-preview-circle i');
    if (icon) {
        icon.style.color = cb.checked ? '#ffffff' : '#1e293b';
    }
}

function updateColorCount() {
    var rows = document.querySelectorAll('#attributeColorsTableBody .attr-color-row');
    var count = rows.length;
    var el = document.getElementById('colorCountBadge');
    if (el) el.textContent = count;
    var pill = document.getElementById('pillBadgeColor');
    if (pill) pill.textContent = count;
}

function updateSizeCount() {
    var rows = document.querySelectorAll('#attributeSizesTableBody .attr-size-row');
    var count = rows.length;
    var el = document.getElementById('sizeCountBadge');
    if (el) el.textContent = count;
    var pill = document.getElementById('pillBadgeSize');
    if (pill) pill.textContent = count;
}
</script>
