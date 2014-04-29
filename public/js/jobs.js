$("#jobs_add").validate({
	rules: {
		job_title: {
			required: true
		},
		qualification: {
			required: true
		},
		joining_period: {
			required: true
		},
		post_date: {
			required: true
		},
		expiry_date: {
			required: true
		}
	},
	messages: {
		job_title: {
			required: mes_required
		},
		qualification: {
			required: mes_required
		},
		joining_period: {
			required: mes_required
		},
		post_date: {
            required: mes_required
        },
		expiry_date: {
            required: mes_required
        }
	}
});