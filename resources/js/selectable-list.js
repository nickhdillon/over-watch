export default () => ({
    selectedTickets: [],

	toggleTicket(id) {
		id = String(id)

		this.selectedTickets = this.selectedTickets.includes(id)
			? this.selectedTickets.filter(ticketId => ticketId !== id)
			: [...this.selectedTickets, id]
	},

	isSelected(id) {
		return this.selectedTickets.includes(String(id))
	},

	selectAll(ids) {
		this.selectedTickets = ids.map(String)
	},

	toggleAll(ids) {
		ids = ids.map(String)

		this.selectedTickets =
			this.selectedTickets.length === ids.length ? [] : ids
	},

	clearSelection() {
		this.selectedTickets = []

		if (this.$wire?.selected_release !== undefined) {
			this.$wire.selected_release = null
		}
	},
});