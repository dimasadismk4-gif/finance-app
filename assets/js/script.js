function updateCategoryOptions() {
    const type = document.getElementById('typeSelect')?.value;
    const select = document.getElementById('categorySelect');
    if (!type || !select) return;

    select.innerHTML = '';
    const cats = type === 'income' ? incomeCats : expenseCats;
    cats.forEach(cat => {
        const opt = document.createElement('option');
        opt.value = cat.id;
        opt.textContent = cat.name;
        select.appendChild(opt);
    });
}

// Chart.js defaults
Chart.defaults.font.family = 'Inter';
Chart.defaults.plugins.legend.position = 'bottom';

