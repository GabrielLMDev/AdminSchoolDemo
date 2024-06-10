document.addEventListener("DOMContentLoaded", function () {
    const sidebarItems = document.querySelectorAll("#sidebar a");
    const sections = document.querySelectorAll(".section");

    sidebarItems.forEach(item => {
        item.addEventListener("click", function (event) {
            event.preventDefault();
            sidebarItems.forEach(i => i.classList.remove("active"));
            this.classList.add("active");

            const sectionId = this.getAttribute("data-section");

            sections.forEach(section => section.classList.remove("active-section"));

            document.getElementById(sectionId).classList.add("active-section");
        });
    });
});
