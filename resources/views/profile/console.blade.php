<!-- greeting -->
<div class="distance-md flowline spread overflow">
	<div>
		<span class="text-muted h4">Good Evening, <b data-role="userdisplay" class="themetxt">Admin</b></span>
		<p>information for the day</p>
	</div>
	<div class="flow left">
		<b>Activity scope</b>
		<div class="btn-group except">
			<button class="btn btn-outline-primary active">daily</button>
			<button class="btn btn-outline-primary">weekly</button>
			<button class="btn btn-outline-primary">monthly</button>
			<button class="btn btn-outline-primary">lifetime</button>
		</div>
	</div>
</div>

<div class="stats-row">
	<div class="stat-card modern">
		<div class="icon primary">
			<i class="fa fa-money-bill"></i>
		</div>
		<div class="stats">
			<h3>53,345</h3>
			<span class="text-muted">Total Sales Today</span>
		</div>
	</div>

	<div class="stat-card modern">
		<div class="icon primary">
			<i class="fa fa-flask"></i>
		</div>
		<div class="stats">
			<h3>25</h3>
			<span class="text-muted">Total Daily Jobs</span>
		</div>
	</div>

	<div class="stat-card modern">
		<div class="icon urgent">
			<i class="fa fa-clock"></i>
		</div>
		<div class="stats">
			<h3>5</h3>
			<span class="text-muted">incomplete Jobs</span>
		</div>
	</div>

	<div class="stat-card modern">
		<div class="icon urgent">
			<i class="fa fa-users"></i>
		</div>
		<div class="stats">
			<h3>3</h3>
			<span class="text-muted">Unpaid Jobs</span>
		</div>
	</div>

	<div class="stat-card modern">
		<div class="icon primary">
			<i class="fa fa-file"></i>
		</div>
		<div class="stats">
			<h3>25</h3>
			<span class="text-muted">Reports made</span>
		</div>
	</div>
</div>

	<!-- Main Content Row -->
	<div class="row">
		<!-- Calendar & Attendance -->
		<div class="col-lg-3 mb-4">
			<div class="content-card">
				<div class="card-header-custom">
					<h5 class="card-title">Sales Calendar</h5>
				</div>

				<div class="calendar-header">
					<div class="calendar-nav">
						<button onclick="changeMonth(-1)"><i class="fas fa-chevron-left"></i></button>
					</div>
					<span id="currentMonth" style="font-weight: 600; color: var(--text-dark);">02 October, 2019</span>
					<div class="calendar-nav">
						<button onclick="changeMonth(1)"><i class="fas fa-chevron-right"></i></button>
					</div>
				</div>

				<div class="calendar-grid" id="calendar">
					<div class="calendar-day-header">Sun</div>
					<div class="calendar-day-header">Mon</div>
					<div class="calendar-day-header">Tue</div>
					<div class="calendar-day-header">Wed</div>
					<div class="calendar-day-header">Thu</div>
					<div class="calendar-day-header">Fri</div>
					<div class="calendar-day-header">Sat</div>
				</div>
			</div>
		</div>

		<!-- School Performance Chart -->
		<div class="col-lg-9 mb-4">
			<div class="content-card">
				<div class="card-header-custom">
					<h5 class="card-title">School Performance</h5>
					<span style="font-size: 12px; color: var(--text-muted);">All the data in percentage (%)</span>
				</div>

				<div class="chart-legend">
					<div class="legend-item">
						<div class="legend-dot" style="background: #6b5b95;"></div>
						<span>Govt. School</span>
					</div>
					<div class="legend-item">
						<div class="legend-dot" style="background: #ffa500;"></div>
						<span>Private School</span>
					</div>
					<div class="legend-item">
						<div class="legend-dot" style="background: #50c878;"></div>
						<span>Average School</span>
					</div>
				</div>

				<div class="chart-container">
					<canvas id="performanceChart"></canvas>
				</div>
			</div>
		</div>
	</div>

	<!-- Bottom Row -->
	<div class="row">
		<!-- Activities Notification -->
		<div class="col-lg-8 mb-4">
			<div class="content-card">
				<div class="card-header-custom">
					<h5 class="card-title">Activities Notification</h5>
					<button class="mybtn primary">View All</button>
				</div>

				<div class="activity-item">
					<div class="activity-venue">St. Xavier School</div>
					<div class="activity-date">02 Oct, 2019</div>
					<div class="activity-desc">We are planning to celebrate Gandhi Jayanti on 2nd October in our school. So it is our humble request to all parents and teachers to join us on this special occasion.</div>
				</div>

				<div class="activity-item">
					<div class="activity-venue">Podar International School</div>
					<div class="activity-date">02 Oct, 2019</div>
					<div class="activity-desc">We are planning to celebrate Gandhi Jayanti on 2nd October in our school. So it is our humble request to all parents and teachers to join us on this special occasion.</div>
				</div>
			</div>
		</div>

		<!-- Schools Fees Structure -->
		<div class="col-lg-4 mb-4">
			<div class="content-card">
				<div class="card-header-custom">
					<h5 class="card-title">Schools Fees Structure</h5>
					<span style="font-size: 12px; color: var(--text-muted);">All the data in percentage (%)</span>
				</div>

				<div class="chart-container" style="height: 200px;">
					<canvas id="feesChart"></canvas>
				</div>
			</div>
		</div>
	</div>
</main>

<script>
	// Mobile Sidebar Toggle
	function toggleSidebar() {
		document.getElementById('sidebar').classList.toggle('active');
	}

	// Calendar Generation
	const months = ["January", "February", "March", "April", "May", "June",
				   "July", "August", "September", "October", "November", "December"];
	let currentDate = new Date(2019, 9, 2); // October 2, 2019

	function generateCalendar() {
		const calendar = document.getElementById('calendar');
		const year = currentDate.getFullYear();
		const month = currentDate.getMonth();

		// Update header
		document.getElementById('currentMonth').textContent =
			`${String(month + 1).padStart(2, '0')} ${months[month]}, ${year}`;

		// Clear existing days (keep headers)
		const existingDays = calendar.querySelectorAll('.calendar-day');
		existingDays.forEach(day => day.remove());

		const firstDay = new Date(year, month, 1).getDay();
		const daysInMonth = new Date(year, month + 1, 0).getDate();
		const daysInPrevMonth = new Date(year, month, 0).getDate();

		// Previous month days
		for (let i = firstDay - 1; i >= 0; i--) {
			const day = document.createElement('div');
			day.className = 'calendar-day other-month';
			day.textContent = daysInPrevMonth - i;
			calendar.appendChild(day);
		}

		// Current month days
		const today = 2; // Highlight October 2nd as in image
		const activeDays = [27, 28, 29]; // Green highlighted days

		for (let i = 1; i <= daysInMonth; i++) {
			const day = document.createElement('div');
			day.className = 'calendar-day';
			day.textContent = i;

			if (i === today) {
				day.classList.add('today');
			}
			if (activeDays.includes(i)) {
				day.classList.add('active');
			}
			if ([17, 24].includes(i)) {
				day.classList.add('event');
			}

			calendar.appendChild(day);
		}

		// Next month days
		const remainingCells = 42 - (firstDay + daysInMonth);
		for (let i = 1; i <= remainingCells; i++) {
			const day = document.createElement('div');
			day.className = 'calendar-day other-month';
			day.textContent = i;
			calendar.appendChild(day);
		}
	}

	function changeMonth(direction) {
		currentDate.setMonth(currentDate.getMonth() + direction);
		generateCalendar();
	}

	// Initialize Calendar
	generateCalendar();

	// School Performance Chart
	const performanceCtx = document.getElementById('performanceChart').getContext('2d');
	const performanceChart = new Chart(performanceCtx, {
		type: 'bar',
		data: {
			labels: ['Govt', 'Private', 'Average'],
			datasets: [{
				label: 'Performance',
				data: [85, 65, 75],
				backgroundColor: [
					'#6b5b95',
					'#ffa500',
					'#50c878'
				],
				borderRadius: 8,
				borderSkipped: false,
				barThickness: 40
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					display: false
				},
				tooltip: {
					callbacks: {
						label: function(context) {
							return context.parsed.y + '%';
						}
					}
				}
			},
			scales: {
				y: {
					beginAtZero: true,
					max: 100,
					ticks: {
						callback: function(value) {
							return value + '%';
						},
						color: '#7f8c8d'
					},
					grid: {
						color: 'rgba(0,0,0,0.05)'
					}
				},
				x: {
					ticks: {
						color: '#7f8c8d'
					},
					grid: {
						display: false
					}
				}
			}
		}
	});

	// Fees Structure Chart (Doughnut)
	const feesCtx = document.getElementById('feesChart').getContext('2d');
	const feesChart = new Chart(feesCtx, {
		type: 'doughnut',
		data: {
			labels: ['Govt. School', 'Private School', 'International'],
			datasets: [{
				data: [40, 35, 25],
				backgroundColor: [
					'#6b5b95',
					'#ffa500',
					'#50c878'
				],
				borderWidth: 0,
				cutout: '70%'
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					position: 'bottom',
					labels: {
						usePointStyle: true,
						padding: 15,
						font: {
							size: 11
						}
					}
				}
			}
		}
	});

	// Close sidebar when clicking outside on mobile
	document.addEventListener('click', function(e) {
		const sidebar = document.getElementById('sidebar');
		const toggle = document.querySelector('.mobile-toggle');

		if (window.innerWidth <= 991 &&
			!sidebar.contains(e.target) &&
			!toggle.contains(e.target) &&
			sidebar.classList.contains('active')) {
			sidebar.classList.remove('active');
		}
	});
</script>
