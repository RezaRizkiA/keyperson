/**
 * Composable untuk styling status (appointment, transaction, dll)
 * Digunakan di: Dashboard, Appointments, Transactions
 */
export function useStatusColors() {
    /**
     * Get Tailwind classes untuk badge status
     * @param {string} status - Status: completed, confirmed, pending, cancelled, declined
     * @returns {string} - Tailwind CSS classes
     */
    const getStatusColor = (status) => {
        const colors = {
            completed:
                "bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400",
            confirmed:
                "bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400",
            pending:
                "bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400",
            cancelled:
                "bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400",
            declined:
                "bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400",
        };
        return (
            colors[status] ||
            "bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300"
        );
    };

    /**
     * Get Tailwind classes untuk tipe ledger (credit/debit)
     * @param {string} type - Type: credit atau debit
     * @returns {string} - Tailwind CSS classes
     */
    const getLedgerTypeColor = (type) => {
        return type === "credit"
            ? "text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10"
            : "text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10";
    };

    /**
     * Get status label dalam Bahasa Indonesia
     * @param {string} status - Status key
     * @returns {string} - Label status
     */
    const getStatusLabel = (status) => {
        const labels = {
            completed: "Selesai",
            confirmed: "Dikonfirmasi",
            pending: "Menunggu",
            cancelled: "Dibatalkan",
            declined: "Ditolak",
        };
        return labels[status] || status;
    };

    return {
        getStatusColor,
        getLedgerTypeColor,
        getStatusLabel,
    };
}
