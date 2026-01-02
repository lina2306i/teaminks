final version
@extends('layouts.appW')

@section('contentW')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="display-5 fw-bold text-gradient">Projects & Tasks Calendar</h1>
            <a href="{{ route('leader.projects.index') }}" class="btn btn-outline-light">
                ← Back to List Project
            </a>
        </div>

        <!-- Filtres -->
        <div class="card bg-gray-800 text-white border-0 shadow mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <!-- Recherche -->
                    <div class="col-md-4">
                        <label class="small text-gray-400">Search</label>
                        <input type="text" id="searchInput" class="form-control bg-gray-700 text-white border-0"
                                placeholder="Search by name, team or project...">
                    </div>
                    <!-- Filtre par type -->
                    <div class="col-md-3">
                        <label class="small text-gray-400">Type</label>
                        <select id="typeFilter" class="form-select">
                            <option value="all">All events</option>
                            <option value="project">Projects only</option>
                            <option value="task">Tasks only</option>
                        </select>
                    </div>

                    <!-- Bouton reset -->
                    <div class="col-md-2">
                        <button id="resetFilters" class="btn btn-outline-light w-100">Reset</button>
                    </div>
                    <!-- Filtre par statut col-md-8 -->
                    <div class="mt-4">
                        <label class="small text-gray-400 mb-2 d-block">Status</label>
                        <div id="statusFilter" class="d-flex gap-3 flex-wrap">
                            <button class="btn btn-outline-light active" data-filter="all">All statuses</button>
                            <button class="btn btn-outline-primary" data-filter="active">Active-In Progress</button>
                            <button class="btn btn-outline-success" data-filter="completed">Completed</button>
                            <button class="btn btn-outline-danger" data-filter="overdue">Overdue</button>
                        </div>
                    </div>
                </div>

                <!-- Légende -->
                <div class="mt-4 small text-gray-400">
                    <span class="badge bg-primary me-3">Project (In Progress)</span>
                    <span class="badge bg-warning text-dark">Task</span>
                    <span class="badge bg-success me-3">Completed</span>
                    <span class="badge bg-danger me-3">Overdue</span>
                </div>
            </div>
        </div>

        <!-- Calendrier -->
        <div class="card bg-gray-800 text-white border-0 shadow">
            <div class="card-body p-4">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</section>
<!-- FullCalendar CDN + Tippy.js -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<!-- Tippy.js pour tooltips (léger) -->
<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;
        // Événements passés depuis le controller (JSON)
        const events = @json($events ?? []);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            height: 'auto', //650
            events: events,
            dayMaxEvents: 3,
            eventDisplay: 'block',
            // Personnalisation du rendu des événements
            eventContent: function(arg) {
                const progress = arg.event.extendedProps.progress ?? null;
                const type = arg.event.extendedProps.type;

                if (type === 'project' && progress !== null) {
                    return {
                        html: `
                            <div class="fc-event-main">
                                <div class="project-title fw-bold small text-truncate">${arg.event.title}</div>
                                <div class="progress mt-1" style="height:6px; background: rgba(255,255,255,0.2); border-radius: 3px;">
                                    <div class="progress-bar bg-light" style="width:${progress}%; background: #4f46e5;"></div>
                                </div>
                            </div>
                        `
                    };
                }

                return {
                    html: `<div class="small fw-bold text-truncate">${arg.event.title}</div>`
                };
            },
            // Appliquer couleur selon type/statut
            eventDidMount: function(info) {
                const props = info.event.extendedProps;
                const el = info.el;

                // Couleur de fond par type
                if (props.type === 'project') {
                    el.style.backgroundColor = '#4f46e5'; // indigo
                    el.style.borderColor = '#4f46e5';
                } else if (props.type === 'task') {
                    el.style.backgroundColor = '#f59e0b'; // amber
                    el.style.borderColor = '#f59e0b';
                }

                // Surcharge si overdue
                if (props.status === 'overdue') {
                    el.style.backgroundColor = '#dc2626';
                    el.style.borderColor = '#dc2626';
                } else if (props.status === 'completed') {
                    el.style.backgroundColor = '#16a34a';
                    el.style.borderColor = '#16a34a';
                }

               // let tooltip = info.event.title;
                // Tooltip avancé avec Tippy.js
               let Tooltip = `<strong>${info.event.title}</strong><br><br>`;


                if (props.type === 'project') {
                    tooltip += `\n👥 <strong>Team : </strong> ${props.team || 'None'} \n📊 <strong>Progress : </strong> ${props.progress}% \n 📅 <strong>End : </strong> ${props.end_date || 'Not set'}`;
                } else if (props.type === 'task') {
                    tooltip += `\n📁 <strong>Project : </strong> ${props.project || 'Unknown'}\n👤 <strong>Assigned to : </strong> ${props.assignee || 'Unassigned'} \n ⭐ <strong>Priority:</strong> ${props.difficulty ? props.difficulty.charAt(0).toUpperCase() + props.difficulty.slice(1) : 'Normal'}`;
                }

                tippy(el, {
                    content: tooltip,
                    allowHTML: true,
                    placement: 'top',
                    theme: 'light',
                    animation: 'shift-away',
                    maxWidth: 300
                });

                // info.el.setAttribute('title', tooltip);
            }
        });

        calendar.render();

        // Filtres & Recherche
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
                const projectName = (props.project || '').toLowerCase();
                const assignee = (props.assignee || '').toLowerCase();

                // Recherche dans titre + team + project + assignee
                const matchesSearch = !searchTerm ||
                    title.includes(searchTerm) ||
                    team.includes(searchTerm) ||
                    projectName.includes(searchTerm) ||
                    assignee.includes(searchTerm);

                // Filtre par type
                const matchesType = selectedType === 'all' || props.type === selectedType;

                // Filtre par statut
                let matchesStatus = true;
                if (selectedStatus !== 'all') {
                    if (selectedStatus === 'in_progress') {
                        matchesStatus = props.status === 'in_progress' || props.status === 'todo';
                    } else {
                        matchesStatus = props.status === selectedStatus;
                    }
                }

                // Afficher ou cacher
                event.setProp('display', matchesSearch && matchesType && matchesStatus ? 'auto' : 'none');
            });

            calendar.render(); // Important pour forcer le rafraîchissement
            //calendar.updateSize(); // Force re-render
        }

        // Écouteurs
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

        // Appliquer les filtres dès le chargement (fixe le bug d’affichage initial)
        applyFilters();
    });
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet" />
<style>

    /* Événements */
    .fc-event {
        font-size: 0.8rem;
         border-radius: 8px;
         padding: 4px 6px;
        /* margin: 2px 0;
        cursor: pointer; */
    }

      .project-title {
            font-weight: 600;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

    .progress {
        background:rgba(246, 180, 180, 0.3); /*#7ab5d9 || 0 0 0 0 3  in the transparente bar progress*/
        border-radius: 30px; /*3px  */
        height: 4px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: #127fba;
        border-radius: 30px;  /* 3px */
        transition: width 0.3s ease;
    }
    .fc-daygrid-event {
        padding: 4px 6px;
        border-radius: 6px;
        background-color: #e6cee9 ;
        box-shadow: 0 2px 6px rgba(163, 220, 20, 0.728);  /*  in the shaqow of task */
        margin: 2px 0;
        font-size: 0.8rem;
       /* white-space: normal; */
    }

        .fc-daygrid-day-frame {
            padding: 6px;
            /*background-color: #97c9e4 */
        }

    /*  pour le button of the change in calender  */
    .fc-button-primary {
        background-color: #8587f6 !important;
        border: none !important;
    }
    .fc-button-primary:hover {
        background-color: #5d55f3 !important;
    }
    /* today case   */
    .fc-today {
        background: rgba(241, 101, 119, 0.879) !important; /* rgba(99, 102, 241, 0.15) */
    }
    /* Responsive */
    @media (max-width: 768px) {
        .fc-header-toolbar {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
@endpush
@endsection







--------------------------------------------------------------------------



<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            height: 'auto',
            events: [
                @foreach($projects as $project)
                //@foreach(auth()->user()->projects()->with('team')->get() as $project)
                {
                    title: '{{ addslashes($project->name) }} ({{ $project->progress }}%)',
                    start: '{{ $project->start_date?->format('Y-m-d') }}',
                    end: '{{ optional($project->end_date)->addDay()->format('Y-m-d') }}', // end est exclusive
                    url: '{{ route('leader.projects.show', $project) }}',
                    backgroundColor: '{{ $project->is_overdue ? '#dc3545' : ($project->progress == 100 ? '#28a745' : '#007bff') }}',
                    borderColor: '{{ $project->is_overdue ? '#dc3545' : ($project->progress == 100 ? '#28a745' : '#007bff') }}',
                    textColor: '#fff',
                    extendedProps: {
                        team: '{{ $project->team?->name ?? 'No team' }}',
                        progress: '{{ $project->progress }}'
                    }
                } {{ $loop->last ? '' : ',' }}
                @endforeach
            ],
            eventDidMount: function(info) {
                // Tooltip simple au survol
                info.el.title =
                    info.event.title +
                    '\nTeam: ' + info.event.extendedProps.team +
                    '\nProgress: ' + info.event.extendedProps.progress + '%';
            }
        });

        calendar.render();
    });
</script>


/// filtre + calendar ne afiche ppas

@push('scripts')

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        // const events = @ j son($ projects);  si function calendar return project sinon :

        if (!calendarEl) return;
        const events = @json($events ?? []);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            height: 7000, // Fix principal : hauteur fixe pour forcer rendu initial || 'auto',
            events: events,

            dayMaxEvents: 3, // ⭐ clé anti-chaos
            eventDisplay: 'block',

            // Contenu personnalisé
            eventContent(arg) {
                // const progress = arg.event.extendedProps.progress; maj
                const progress = arg.event.extendedProps.progress ?? null;
                const type = arg.event.extendedProps.type;

                if (type === 'project' && progress !== null) {}

                    return {
                        html: `
                            <div class=" fc-event-main small">
                                <div class=" fw-bold small text-truncate">
                                    ${arg.event.title}
                                </div>
                                <div class="progress small mt-1"  style="height:6px; background:rgba(255,255,255,0.2); border-radius:3px;">
                                    <div class="progress-bar bg-light" style="width:${progress}% ; border-radius:3px;"></div>
                                </div>
                            </div>
                        `
                    };
                }

                // Pour les tâches ou projets sans progress
                return {
                    html: `<div class="fw-bold small text-truncate">${arg.event.title}</div>`
                };

            },

            // Tooltip au survol

           // eventDidMount(info) {
                // Tooltip clean pour leader
              /*  info.el.setAttribute('title',`📌 ${info.event.title}
                👥 Team: ${info.event.extendedProps.team}
                📊 Progress: ${info.event.extendedProps.progress}%`
                );*/
                /*info.el.title =
                    info.event.title +
                    '\nTeam: ' + info.event.extendedProps.team +
                    '\nProgress: ' + info.event.extendedProps.progress + '%';*/
           // }
            //maj
            eventDidMount: function(info) {
                const props = info.event.extendedProps;
                let tooltip = info.event.title;

                if (props.type === 'project') {
                    tooltip += `\n👥 Team: ${props.team  || 'No team'}\n📊 Progress: ${props.progress  ?? 0}%`;
                } else if (props.type === 'task') {
                    tooltip += `\n📁 Project: ${props.project  || 'None'}\n👤 Assigned: ${props.assignee || 'Unassigned'}`;
                }
                //info.el.title = tip;
                info.el.setAttribute('title', tooltip.replace(/\n/g, ' • '));

                // info.el.setAttribute('title', tooltip);
            }



        });

        calendar.render();

        //maj

        // === FILTRES & RECHERCHE ===
        const searchInput = document.getElementById('searchInput');
        const filterButtons = document.querySelectorAll('[data-filter]');

        function applyFilter() {
            const search = searchInput.value.toLowerCase().trim();
            const activeFilter = document.querySelector('[data-filter].active')?.dataset.filter || 'all';

            calendar.getEvents().forEach(event => {
                const title = event.title.toLowerCase();
                const team = (event.extendedProps.team || '').toLowerCase();
                const projectName = (event.extendedProps.project || '').toLowerCase();
                const status = event.extendedProps.status || 'active';

                const matchesSearch = !search ||
                    title.includes(search) ||
                    team.includes(search) ||
                    projectName.includes(search);

                const matchesFilter = activeFilter === 'all' || status === activeFilter;

                event.setProp('display', matchesSearch && matchesFilter ? 'auto' : 'none');
            });

            calendar.updateSize(); // Force re-render après filtre
        }

        // Événements
        searchInput.addEventListener('input', applyFilter);

        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                applyFilter();
            });
        });

        // Fix initial : appelle filter(applyFilters() appelé au load) == applyFilters au load pour forcer display 'auto'  Appliquer le filtre "All" au chargement
        applyFilter();
    });
</script>

@endpush


@endsection


@push('styles')
    <style>
       /*
           <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />

       .fc-project {
        }

        .fc-project-title {
            font-weight: 600;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .fc-progress {
            background: rgba(255,255,255,0.25);
            border-radius: 10px;
            height: 4px;
            overflow: hidden;
        }
        .fc-progress-bar {
            height: 100%;
            background: #a3b1ba;
            border-radius: 10px;
            transition: width 0.3s ease;
        }


        */
        .fc-event {  font-size: 0.75rem; border-radius: 8px; padding: 4px 6px; }



        .progress {
             background: rgba(216, 138, 138, 0.3); // 0 0 0 0 3
            border-radius: 10px;
            height: 4px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: #a3b1ba;
            border-radius: 10px;
            transition: width 0.3s ease;
         }

        .fc-daygrid-event {
            padding: 4px 6px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            margin: 2px 0;
             font-size: 0.8rem;
        }

        .fc-daygrid-day-frame {
            padding: 6px;
        }

        .fc-button-primary {
            background-color: #6366f1 !important;
            border: none !important;
        }
        .fc-button-primary:hover {
            background-color: #4f46e5 !important;
        }
        .fc-today {
            background: rgba(99, 102, 241, 0.15) !important;
        }
    </style>
@endpush
