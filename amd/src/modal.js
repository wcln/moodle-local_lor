document.onload = () => {
    const embedBtn = document.querySelector('#embed');
    const modal = document.querySelector('.lor-modal');

    document.querySelectorAll('.close').forEach((closeBtn) => {
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    });

    embedBtn.addEventListener('click', (e) => {
        e.preventDefault();
        modal.style.display = "block";
    });

    window.onclick = (e) => {
        e.preventDefault();
        if (e.target === modal) {
            modal.style.display = "none";
        }
    };
};


