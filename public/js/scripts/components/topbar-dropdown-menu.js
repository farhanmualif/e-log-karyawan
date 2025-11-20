function toggleUserMenu(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('hidden');
}

document.addEventListener('click', function (event) {
    const dropdown = document.getElementById('userDropdown');
    const container = document.getElementById('userMenuContainer');

    if (container && !container.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
