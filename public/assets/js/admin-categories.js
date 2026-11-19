/**
 * Admin Categories JS
 */

function toggleNewMainCat() {
    const select = document.getElementById('parent_category_id');
    const input = document.getElementById('new_main_category');
    const wrapper = document.getElementById('new_main_category_wrapper');
    if (!select || !input) return;

    if (select.value === 'NEW') {
        if (wrapper) wrapper.style.display = 'flex';
        input.style.display = 'block';
        input.required = true;
        input.focus();
    } else {
        if (wrapper) wrapper.style.display = 'none';
        input.style.display = 'none';
        input.required = false;
    }
}

function generateSlug() {
    const nameInput = document.getElementById('subcategory_name');
    const slugInput = document.getElementById('subcategory_slug');
    if (!nameInput || !slugInput) return;

    const slug = nameInput.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    slugInput.value = slug;
}

document.addEventListener('DOMContentLoaded', function() {
    const parentCatSelect = document.getElementById('parent_category_id');
    if (parentCatSelect) {
        parentCatSelect.addEventListener('change', toggleNewMainCat);
        toggleNewMainCat(); // Initial state check
    }

    const subcatName = document.getElementById('subcategory_name');
    if (subcatName) {
        subcatName.addEventListener('keyup', generateSlug);
    }
});
