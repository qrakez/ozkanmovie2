document.querySelectorAll('.content section').forEach(section => {
    section.style.display = 'none';
});

const defaultSectionId = 'section1';
const defaultSection = document.getElementById(defaultSectionId);
if (defaultSection) {
    defaultSection.style.display = 'block';
}

document.querySelectorAll('.sidebar ul li a').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();

        const targetId = this.getAttribute('href').substring(1);

        document.querySelectorAll('.content section').forEach(section => {
            section.style.display = 'none';
        });

        const targetSection = document.getElementById(targetId);
        if (targetSection) {
            targetSection.style.display = 'block';
        }
    });
});