import Chart from 'chart.js/auto';

const sidebar = document.querySelector('[data-sidebar]');
const overlay = document.querySelector('[data-sidebar-overlay]');
const sidebarToggle = document.querySelector('[data-sidebar-toggle]');

function setSidebar(open) {
    if (!sidebar || !overlay) {
        return;
    }

    sidebar.classList.toggle('-translate-x-full', !open);
    overlay.classList.toggle('hidden', !open);
    sidebarToggle?.setAttribute('aria-expanded', open ? 'true' : 'false');
}

sidebarToggle?.addEventListener('click', () => {
    setSidebar(sidebar?.classList.contains('-translate-x-full') ?? false);
});

overlay?.addEventListener('click', () => setSidebar(false));

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        setSidebar(false);
    }
});

document.querySelectorAll('[data-modal-open]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
        const dialog = document.getElementById(trigger.dataset.modalOpen);
        dialog?.showModal();
    });
});

document.querySelectorAll('[data-modal-close]').forEach((button) => {
    button.addEventListener('click', () => {
        button.closest('dialog')?.close();
    });
});

document.querySelectorAll('dialog[data-modal]').forEach((dialog) => {
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();
        }
    });
});

const publicMenuToggle = document.querySelector('[data-public-menu-toggle]');
const publicMenu = document.querySelector('[data-public-menu]');

publicMenuToggle?.addEventListener('click', () => {
    if (!publicMenu) {
        return;
    }

    const isHidden = publicMenu.classList.toggle('hidden');
    publicMenuToggle.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
});

const bookingEstimator = document.querySelector('[data-booking-estimator]');

if (bookingEstimator) {
    const startInput = bookingEstimator.querySelector('[data-booking-start]');
    const endInput = bookingEstimator.querySelector('[data-booking-end]');
    const daysEl = bookingEstimator.querySelector('[data-estimate-days]');
    const totalEl = bookingEstimator.querySelector('[data-estimate-total]');

    const vehicleType = bookingEstimator.dataset.vehicleType;
    const dailyRate = Number(bookingEstimator.dataset.dailyRate || 0);
    const carInsuranceRate = Number(bookingEstimator.dataset.carInsuranceRate || 0.1);
    const motorcycleDiscountRate = Number(bookingEstimator.dataset.motorcycleDiscountRate || 0.1);
    const motorcycleMinDays = Number(bookingEstimator.dataset.motorcycleMinDays || 7);

    const rupiahFormatter = new Intl.NumberFormat('id-ID', {
        maximumFractionDigits: 0,
    });

    const updateEstimate = () => {
        if (!startInput?.value || !endInput?.value || !daysEl || !totalEl) {
            return;
        }

        const start = new Date(`${startInput.value}T00:00:00`);
        const end = new Date(`${endInput.value}T00:00:00`);

        if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime()) || end < start) {
            daysEl.textContent = 'Tanggal tidak valid';
            totalEl.textContent = '—';
            return;
        }

        const diffDays = Math.round((end - start) / 86400000) + 1;
        let total = dailyRate * diffDays;

        if (vehicleType === 'car') {
            total += total * carInsuranceRate;
        } else if (vehicleType === 'motorcycle' && diffDays >= motorcycleMinDays) {
            total -= total * motorcycleDiscountRate;
        }

        daysEl.textContent = `${diffDays} hari`;
        totalEl.textContent = `Rp ${rupiahFormatter.format(Math.round(total))}`;
    };

    startInput?.addEventListener('change', () => {
        if (endInput && startInput.value && (!endInput.value || endInput.value < startInput.value)) {
            endInput.value = startInput.value;
        }
        if (endInput && startInput.value) {
            endInput.min = startInput.value;
        }
        updateEstimate();
    });

    endInput?.addEventListener('change', updateEstimate);
    updateEstimate();
}

const chartDataElement = document.getElementById('dashboard-chart-data');

if (chartDataElement) {
    const data = JSON.parse(chartDataElement.textContent);
    const rupiah = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    });
    const compactRupiah = new Intl.NumberFormat('id-ID', {
        notation: 'compact',
        maximumFractionDigits: 1,
    });

    const revenueCanvas = document.getElementById('revenueChart');

    if (revenueCanvas) {
        new Chart(revenueCanvas, {
            type: 'line',
            data: {
                labels: data.revenue.labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data.revenue.totals,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.12)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#4f46e5',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => ` ${rupiah.format(context.parsed.y ?? 0)}`,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => `Rp ${compactRupiah.format(value)}`,
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.15)',
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
            },
        });
    }

    const vehicleCanvas = document.getElementById('vehicleStatusChart');

    if (vehicleCanvas) {
        new Chart(vehicleCanvas, {
            type: 'doughnut',
            data: {
                labels: data.vehicles.labels,
                datasets: [{
                    data: data.vehicles.totals,
                    backgroundColor: data.vehicles.colors,
                    borderWidth: 0,
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 16,
                        },
                    },
                },
            },
        });
    }
}
