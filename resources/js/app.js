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

const rentalEstimator = document.querySelector('[data-rental-estimator]');

if (rentalEstimator) {
    const vehicleSelect = rentalEstimator.querySelector('[data-rental-vehicle]');
    const startInput = rentalEstimator.querySelector('[data-rental-start]');
    const endInput = rentalEstimator.querySelector('[data-rental-end]');
    const daysEl = rentalEstimator.querySelector('[data-rental-days]');
    const rateEl = rentalEstimator.querySelector('[data-rental-rate]');
    const ruleEl = rentalEstimator.querySelector('[data-rental-rule]');
    const totalEl = rentalEstimator.querySelector('[data-rental-total]');

    const carInsuranceRate = Number(rentalEstimator.dataset.carInsuranceRate || 0.1);
    const motorcycleDiscountRate = Number(rentalEstimator.dataset.motorcycleDiscountRate || 0.1);
    const motorcycleMinDays = Number(rentalEstimator.dataset.motorcycleMinDays || 7);

    const rupiahFormatter = new Intl.NumberFormat('id-ID', {
        maximumFractionDigits: 0,
    });

    const getVehicleConfig = () => {
        if (vehicleSelect) {
            const option = vehicleSelect.options[vehicleSelect.selectedIndex];
            return {
                type: option?.dataset.vehicleType || '',
                rate: Number(option?.dataset.dailyRate || 0),
            };
        }

        return {
            type: rentalEstimator.dataset.vehicleType || '',
            rate: Number(rentalEstimator.dataset.dailyRate || 0),
        };
    };

    const updateRentalEstimate = () => {
        if (!daysEl || !totalEl) {
            return;
        }

        const { type, rate } = getVehicleConfig();

        if (rateEl) {
            rateEl.textContent = rate > 0 ? `Rp ${rupiahFormatter.format(Math.round(rate))}` : '—';
        }

        if (!startInput?.value || !endInput?.value || !type || rate <= 0) {
            daysEl.textContent = '—';
            totalEl.textContent = '—';
            if (ruleEl) {
                ruleEl.textContent = 'Pilih kendaraan dan rentang tanggal untuk melihat estimasi biaya.';
            }
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
        let total = rate * diffDays;

        if (type === 'car') {
            total += total * carInsuranceRate;
            if (ruleEl) {
                ruleEl.textContent = `Termasuk asuransi mobil ${Math.round(carInsuranceRate * 100)}%.`;
            }
        } else if (type === 'motorcycle' && diffDays >= motorcycleMinDays) {
            total -= total * motorcycleDiscountRate;
            if (ruleEl) {
                ruleEl.textContent = `Diskon motor mingguan ${Math.round(motorcycleDiscountRate * 100)}% diterapkan (≥ ${motorcycleMinDays} hari).`;
            }
        } else if (ruleEl) {
            ruleEl.textContent = type === 'motorcycle'
                ? `Tarif standar motor (diskon ${Math.round(motorcycleDiscountRate * 100)}% mulai ${motorcycleMinDays} hari).`
                : 'Tarif standar.';
        }

        daysEl.textContent = `${diffDays} hari`;
        totalEl.textContent = `Rp ${rupiahFormatter.format(Math.round(total))}`;
    };

    vehicleSelect?.addEventListener('change', updateRentalEstimate);
    startInput?.addEventListener('change', () => {
        if (endInput && startInput.value && (!endInput.value || endInput.value < startInput.value)) {
            endInput.value = startInput.value;
        }
        if (endInput && startInput.value) {
            endInput.min = startInput.value;
        }
        updateRentalEstimate();
    });
    endInput?.addEventListener('change', updateRentalEstimate);
    updateRentalEstimate();
}

const paymentForm = document.querySelector('[data-payment-form]');

if (paymentForm) {
    const rentalSelect = paymentForm.querySelector('[data-payment-rental-select]');
    const amountInput = paymentForm.querySelector('[data-payment-amount]');
    const remainingEl = paymentForm.querySelector('[data-payment-remaining]');
    const statusSelect = paymentForm.querySelector('[data-payment-status]');
    const paidAtWrapper = paymentForm.querySelector('[data-paid-at-wrapper]');

    const rupiahFormatter = new Intl.NumberFormat('id-ID', {
        maximumFractionDigits: 0,
    });

    const syncPaymentRental = (fillAmount = false) => {
        if (!rentalSelect) {
            return;
        }

        const option = rentalSelect.options[rentalSelect.selectedIndex];
        const remaining = Number(option?.dataset.remaining || 0);

        if (remainingEl) {
            remainingEl.textContent = option?.value
                ? `Sisa tagihan: Rp ${rupiahFormatter.format(Math.round(remaining))}`
                : 'Pilih transaksi rental untuk melihat sisa tagihan.';
        }

        if (fillAmount && amountInput && remaining > 0) {
            amountInput.value = String(Math.round(remaining));
        }
    };

    const syncPaidAtVisibility = () => {
        if (!statusSelect || !paidAtWrapper) {
            return;
        }

        paidAtWrapper.classList.toggle('hidden', statusSelect.value !== 'paid');
    };

    rentalSelect?.addEventListener('change', () => syncPaymentRental(true));
    statusSelect?.addEventListener('change', syncPaidAtVisibility);
    syncPaymentRental(false);
    syncPaidAtVisibility();
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
