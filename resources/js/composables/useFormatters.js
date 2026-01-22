/**
 * Composable untuk format currency dan date
 * Digunakan di: Dashboard, Transactions, Appointments, dll
 */
export function useFormatters() {
    /**
     * Format angka menjadi mata uang Rupiah
     * @param {number} amount - Jumlah yang akan diformat
     * @returns {string} - Format: Rp 1.000.000
     */
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount || 0);
    };

    /**
     * Format tanggal dengan locale Indonesia
     * @param {string|Date} dateString - Tanggal yang akan diformat
     * @returns {string} - Format: 14 Jan 2026, 10:30
     */
    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    /**
     * Format tanggal tanpa waktu
     * @param {string|Date} dateString - Tanggal yang akan diformat
     * @returns {string} - Format: 14 Januari 2026
     */
    const formatDateOnly = (dateString) => {
        return new Date(dateString).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });
    };

    /**
     * Format waktu saja
     * @param {string|Date} dateString - Tanggal yang akan diformat
     * @returns {string} - Format: 10:30
     */
    const formatTime = (dateString) => {
        return new Date(dateString).toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    return {
        formatCurrency,
        formatDate,
        formatDateOnly,
        formatTime,
    };
}
