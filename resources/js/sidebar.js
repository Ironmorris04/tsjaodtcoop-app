const sidebar = document.getElementById("sidebar");
const mainContent = document.getElementById("mainContent");
const menuItems = document.querySelectorAll(".menu-item a");
let isPinned = false;

// Expand sidebar on hover
sidebar.addEventListener("mouseenter", function () {
    if (!isPinned) {
        sidebar.classList.add("expanded");
    }
});

// Collapse sidebar when mouse leaves (only if not pinned)
sidebar.addEventListener("mouseleave", function () {
    if (!isPinned) {
        sidebar.classList.remove("expanded");
    }
});

// Pin sidebar when clicking menu items
menuItems.forEach((item) => {
    item.addEventListener("click", function (e) {
        e.preventDefault();

        // Remove active class from all items
        menuItems.forEach((i) => i.parentElement.classList.remove("active"));

        // Add active class to clicked item
        this.parentElement.classList.add("active");

        // Pin the sidebar
        isPinned = true;
        sidebar.classList.add("pinned");
        sidebar.classList.remove("expanded");
    });
});

// Unpin sidebar when clicking on main content
mainContent.addEventListener("click", function () {
    if (isPinned) {
        isPinned = false;
        sidebar.classList.remove("pinned");
    }
});

// Prevent clicks inside sidebar from triggering the mainContent click
sidebar.addEventListener("click", function (e) {
    e.stopPropagation();
});
