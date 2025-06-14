document.addEventListener("DOMContentLoaded", function () {
    "use strict";
    // =================================
    // Tooltip
    // =================================
    const tooltipTriggerList = Array.from(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.forEach((tooltipTriggerEl) => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // =================================
    // Popover
    // =================================
    var popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    // =================================
    // Hide preloader
    // =================================
    var preloader = document.querySelector(".preloader");
    if (preloader) {
        preloader.style.display = "none";
    }
    // =================================
    // Increment & Decrement
    // =================================
    const quantityButtons = document.querySelectorAll(".minus, .add");
    // console.log(quantityButtons);
    if (!quantityButtons.length) return; // tidak ada tombol, selesai

    // formatter “Rp 100.000”
    const formatRupiah = (n) =>
        new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(n);

    quantityButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            /*––─ 1. Ambil input qty ───*/
            const qtyInput = btn.closest("div")?.querySelector(".qty");
            if (!qtyInput) return; // input tak ditemukan

            /*––– 2. Hitung nilai baru ───*/
            let qty = parseInt(qtyInput.value, 10) || 0;
            const isAdd = btn.classList.contains("add");
            qty = isAdd ? qty + 1 : Math.max(0, qty - 1);
            qtyInput.value = qty;

            /*––– 3. Cari elemen harga (jika ada) ───*/
            const targetId = qtyInput.dataset.targetId; // undefined jika attr tak ada
            console.log(qtyInput.dataset)
            if (!targetId) return; // tidak ada data-target-id

            const priceEl = document.getElementById(targetId);
            if (!priceEl) return; // elemen harga tak ada

            /*––– 4. Hitung & tampilkan total harga ───*/
            const unitPrice = Number(priceEl.dataset.price); // harga satuan
            const total = unitPrice * qty;
            priceEl.textContent = formatRupiah(total); // “Rp …”
        });
    });
});
