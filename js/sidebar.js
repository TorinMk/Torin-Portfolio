const menuToggle = document.querySelector(".menu-toggle");
const sidebar = document.querySelector(".sidebar-items");

menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("active");
});