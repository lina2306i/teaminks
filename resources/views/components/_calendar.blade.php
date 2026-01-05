


 <!-- Filters -->
        <div class="card bg-gray-800 text-white border-0 shadow mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label class="small text-gray-400 mb-1">Search</label>
                        <input type="text" id="searchInput" class="form-control bg-gray-700 text-white border-0"
                               placeholder="Search by name, team, project, or assignee...">
                    </div>
                    <!-- Type Filter -->
                    <div class="col-md-3">
                        <label class="small text-gray-400 mb-1">Type</label>
                        <select id="typeFilter" class="form-select bg-gray-700 text-white border-0">
                            <option value="all">All Events</option>
                            <option value="project">Projects</option>
                            <option value="task">Tasks</option>
                        </select>
                    </div>
                    <!-- Reset Button -->
                    <div class="col-md-2 align-self-end">
                        <button id="resetFilters" class="btn btn-outline-light w-100">Reset Filters</button>
                    </div>
                    <!-- Status Filter -->
                    <div class="col-12 mt-4">
                        <label class="small text-gray-400 mb-2 d-block">Status</label>
                        <div id="statusFilter" class="d-flex gap-3 flex-wrap">
                            <button class="btn btn-outline-light active" data-filter="all">All Statuses</button>
                            <button class="btn btn-outline-primary" data-filter="in_progress">Active/In Progress</button>
                            <button class="btn btn-outline-success" data-filter="completed">Completed</button>
                            <button class="btn btn-outline-danger" data-filter="overdue">Overdue</button>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="mt-4 small text-gray-400">
                    <span class="badge bg-primary me-2">Project (In Progress)</span>
                    <span class="badge bg-warning text-dark me-2">Task</span>
                    <span class="badge bg-success me-2">Completed</span>
                    <span class="badge bg-danger me-2">Overdue</span>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="card bg-gray-800 text-white border-0 shadow">
            <div class="card-body p-4">
                <div id="calendar"></div>
            </div>
        </div>



@once

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
        <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
        <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const calendarEl = document.getElementById('calendar');
                if (!calendarEl) {
                    console.error('Calendar element not found!');
                    return;
                }

                // Events from Laravel (ensure correct format)
                const events = @json($events ?? []);

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    height: 'auto',
                    events: events,
                    dayMaxEvents: 3,
                    eventDisplay: 'block',
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    // Custom event rendering
                    eventContent: function(arg) {
                        const props = arg.event.extendedProps;
                        const type = props.type;

                        if (type === 'project' && props.progress !== null) {
                            return {
                                html: `
                                    <div class="fc-event-main">
                                        <div class="project-title fw-bold small text-truncate">${arg.event.title}</div>
                                        <div class="progress mt-1" style="height: 6px; background: rgba(255,255,255,0.2); border-radius: 3px;">
                                            <div class="progress-bar bg-primary" style="width: ${props.progress}%;"></div>
                                        </div>
                                    </div>
                                `
                            };
                        }

                        return {
                            html: `<div class="small fw-bold text-truncate">${arg.event.title}</div>`
                        };
                    },
                    // Event styling and tooltips
                    eventDidMount: function(info) {
                        const props = info.event.extendedProps;
                        const el = info.el;

                        // Apply colors based on type/status
                        if (props.type === 'project') {
                            el.style.backgroundColor = '#4f46e5'; // Indigo
                            el.style.borderColor = '#4f46e5';
                        } else if (props.type === 'task') {
                            el.style.backgroundColor = '#f59e0b'; // Amber
                            el.style.borderColor = '#f59e0b';
                        }

                        if (props.status === 'overdue') {
                            el.style.backgroundColor = '#dc2626'; // Red
                            el.style.borderColor = '#dc2626';
                        } else if (props.status === 'completed') {
                            el.style.backgroundColor = '#16a34a'; // Green
                            el.style.borderColor = '#16a34a';
                        }

                        // Tooltip content
                        let tooltipContent = `📌<strong>${info.event.title}</strong><br>`;
                        if (props.type === 'project') {
                            tooltipContent += `
                            👥  <strong>Team:</strong> ${props.team || 'None'}<br>
                                📊   <strong>Progress:</strong> ${props.progress || 0}%<br>
                                📅 <strong>End:</strong> ${props.end_date || 'Not set'}
                            `;
                        } else if (props.type === 'task') {
                            tooltipContent += `
                            📁 <strong>Project:</strong> ${props.project || 'Unknown'}<br>
                            👤 <strong>Assigned to:</strong> ${props.assignee || 'Unassigned'}<br>
                            ⭐ <strong>Priority:</strong> ${props.difficulty ? props.difficulty.charAt(0).toUpperCase() + props.difficulty.slice(1) : 'Normal'}
                            `;
                        }

                        tippy(el, {
                            content: tooltipContent,
                            allowHTML: true,
                            placement: 'top',
                            theme: 'dark',
                            animation: 'shift-away',
                            maxWidth: 300
                        });
                    },
                    // Clickable events
                    eventClick: function(info) {
                        const props = info.event.extendedProps;
                        if (props.type === 'project' && props.project_id) {
                            window.location.href = '{{ route("leader.projects.show", ":id") }}'.replace(':id', props.project_id);
                        } else if (props.type === 'task' && props.task_id) {
                            window.location.href = '{{ route("leader.tasks.show", ":id") }}'.replace(':id', props.task_id);
                        }
                    }
                });

                calendar.render();

                // Filter & Search Logic
                const searchInput = document.getElementById('searchInput');
                const typeFilter = document.getElementById('typeFilter');
                const statusButtons = document.querySelectorAll('#statusFilter button');
                const resetBtn = document.getElementById('resetFilters');

                function applyFilters() {
                    const searchTerm = searchInput.value.trim().toLowerCase();
                    const selectedType = typeFilter.value;
                    const activeStatusBtn = document.querySelector('#statusFilter button.active');
                    const selectedStatus = activeStatusBtn ? activeStatusBtn.dataset.filter : 'all';

                    calendar.getEvents().forEach(event => {
                        const props = event.extendedProps;
                        const title = event.title.toLowerCase();
                        const team = (props.team || '').toLowerCase();
                        const project = (props.project || '').toLowerCase();
                        const assignee = (props.assignee || '').toLowerCase();

                        const matchesSearch = !searchTerm ||
                            title.includes(searchTerm) ||
                            team.includes(searchTerm) ||
                            project.includes(searchTerm) ||
                            assignee.includes(searchTerm);

                        const matchesType = selectedType === 'all' || props.type === selectedType;

                        let matchesStatus = true;
                        if (selectedStatus !== 'all') {
                            if (selectedStatus === 'in_progress') {
                                matchesStatus = props.status === 'in_progress' || props.status === 'todo';
                            } else {
                                matchesStatus = props.status === selectedStatus;
                            }
                        }

                        event.setProp('display', matchesSearch && matchesType && matchesStatus ? 'auto' : 'none');
                    });

                    calendar.render();
                }

                // Event Listeners
                searchInput.addEventListener('input', applyFilters);
                typeFilter.addEventListener('change', applyFilters);
                statusButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        statusButtons.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        applyFilters();
                    });
                });
                resetBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    typeFilter.value = 'all';
                    statusButtons.forEach(b => b.classList.remove('active'));
                    document.querySelector('#statusFilter button[data-filter="all"]').classList.add('active');
                    applyFilters();
                });

                // Initial filter application
                applyFilters();
            });
        </script>
    @endpush

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet" />
        <style>
            /* Calendar container */
            .fc {
                background: transparent;
                color: #e5e7eb;
            }

            /* Events */
            .fc-event {
                font-size: 0.8rem !important;
                border-radius: 8px !important;
                padding: 2px 6px !important;
                margin: 1px 0 !important;
                cursor: pointer !important;
                border: none !important;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
            }


            .project-title {
                font-weight: 600 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            /* Progress bar in events  4 -- 6*/
            .progress {
                background: rgba(255, 255, 255, 0.2) !important;
                border-radius: 3px !important;
                height: 4px !important;
                overflow: hidden !important;
            }

            .progress-bar {
                height: 100% !important;
                background: #127fba!important; /*#4f46e5;*/
                border-radius: 3px !important;
                transition: width 0.3s ease !important;
            }

            /* Day cells */
            .fc-daygrid-day-frame {
                padding: 6px;
            }
            .fc-daygrid-event {
            /* background-color: transparent !important;*/ /*  eleve le collour de progress bar   */
                border-radius: 6px !important;
                margin: 1px 0 !important;
                font-size: 0.75rem !important;
            }

            /* Fix Buttons interactivity*/
            /*  pour le button of the change in calender  */
            .fc-button-primary {
                background-color: #8587f6 !important;
                border: 1px solid #4f46e5 !important;
                color: #fff !important;
                border-radius: 6px;
                transition: background-color 0.2s !important;
            }

            /*.fc-button-primary:hover {
                background-color: #5d55f3 !important;
                border-color: #4338ca !important;
            }

            .fc-button-primary:focus {
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3) !important;
            }
            */
            .fc-button-primary:hover,
            .fc-button-primary:focus,
            .fc-button-primary:active {
                background-color: #4f46e5 !important;
                border-color: #4f46e5 !important;
                color: white !important;
                box-shadow: none !important;
            }

            .fc-button-primary:not(:disabled):not(.fc-button-active):hover {
                background-color: #4f46e5 !important;
            }

            /* Today cell         background: rgba(79, 70, 229, 0.15) !important;  */
            .fc-day-today {
                background: rgba(99, 102, 241, 0.15) !important;
                border: 1px solid #6366f1 !important;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .fc-header-toolbar {
                flex-direction: column !important;
                    gap: 10px !important;
                    align-items: center;
                }
                .fc-button-group {
                    width: 100%;
                    display: flex;
                    justify-content: center;
                }
                .fc-event {
                    font-size: 0.7rem !important;
                }
            }

            /* Tippy.js dark theme */
            .tippy-box[data-theme~='dark'] {
                background-color: #849dc2 !important; /* 1f2937*/
                color: #e5e7eb !important;
                border: 1px solid #374151;
                border-radius: 6px;
            }

            .tippy-box[data-theme~='dark'] .tippy-arrow {
                color: #849dc2 !important; /* 1f2937*/
            }
        </style>
    @endpush

@endonce
