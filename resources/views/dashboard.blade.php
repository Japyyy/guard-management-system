@extends('layouts.app')

@section('content')
    <div class="h-[calc(100vh-13.5rem)] flex flex-col gap-5 overflow-hidden">
        <div class="flex items-center justify-between shrink-0">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Operational Overview</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Real-time monitoring for personnel, birthdays, and license compliance.
                </p>
            </div>

            <div class="rounded-2xl bg-gradient-to-r from-slate-900 to-slate-700 px-4 py-2 text-sm font-semibold text-white shadow-lg">
                Live Monitoring Panel
            </div>
        </div>

        <div class="grid grid-cols-2 xl:grid-cols-5 gap-4 shrink-0">
            <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Total Guards</p>
                        <h3 id="cardTotalGuards" class="mt-3 text-3xl font-bold text-slate-900">{{ $totalGuards }}</h3>
                    </div>
                    <div class="h-11 w-11 rounded-2xl bg-slate-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V4H2v16h5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20h6M12 16v4M8 8a4 4 0 118 0 4 4 0 01-8 0Zm-1 8a5 5 0 0110 0" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">All active personnel records stored in the system.</p>
            </div>

            <div class="rounded-3xl border border-emerald-200 bg-gradient-to-br from-white to-emerald-50 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Active</p>
                        <h3 id="cardActiveGuards" class="mt-3 text-3xl font-bold text-emerald-600">0</h3>
                    </div>
                    <div class="h-11 w-11 rounded-2xl bg-emerald-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Guards with licenses beyond 60 days from expiry.</p>
            </div>

            <div class="rounded-3xl border border-red-200 bg-gradient-to-br from-white to-red-50 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Expired</p>
                        <h3 id="cardExpiredLicenses" class="mt-3 text-3xl font-bold text-red-600">0</h3>
                    </div>
                    <div class="h-11 w-11 rounded-2xl bg-red-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-red-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Licenses already past due and requiring action.</p>
            </div>

            <div class="rounded-3xl border border-amber-200 bg-gradient-to-br from-white to-amber-50 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">30 Days</p>
                        <h3 id="cardExpiring30Days" class="mt-3 text-3xl font-bold text-amber-600">0</h3>
                    </div>
                    <div class="h-11 w-11 rounded-2xl bg-amber-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-amber-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20Z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Urgent renewal window requiring immediate follow-up.</p>
            </div>

            <div class="rounded-3xl border border-yellow-200 bg-gradient-to-br from-white to-yellow-50 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">60 Days</p>
                        <h3 id="cardExpiring60Days" class="mt-3 text-3xl font-bold text-yellow-600">0</h3>
                    </div>
                    <div class="h-11 w-11 rounded-2xl bg-yellow-100 flex items-center justify-center">
                        <svg class="h-5 w-5 text-yellow-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20Z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-xs text-slate-400">Advance warning period for upcoming license renewals.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 flex-1 min-h-0">
            <div class="xl:col-span-4 rounded-3xl border border-pink-200 bg-gradient-to-br from-white to-pink-50/70 p-5 shadow-sm flex flex-col min-h-0">
                <div class="mb-4 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Birthday Today</h3>
                        <p class="mt-1 text-sm text-slate-500">Celebrants as of the current date</p>
                    </div>
                    <div id="birthdayTodayCount" class="rounded-2xl bg-pink-100 px-3 py-2 text-xs font-semibold text-pink-700">0</div>
                </div>

                <div id="birthdayTodayList" class="flex-1 min-h-0 overflow-y-auto space-y-3 pr-1"></div>
            </div>

            <div class="xl:col-span-4 rounded-3xl border border-blue-200 bg-gradient-to-br from-white to-blue-50/70 p-5 shadow-sm flex flex-col min-h-0">
                <div class="mb-4 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Upcoming Birthdays</h3>
                        <p class="mt-1 text-sm text-slate-500">Nearest scheduled birthdays</p>
                    </div>
                    <div id="upcomingBirthdaysCount" class="rounded-2xl bg-blue-100 px-3 py-2 text-xs font-semibold text-blue-700">0</div>
                </div>

                <div id="upcomingBirthdaysList" class="flex-1 min-h-0 overflow-y-auto space-y-3 pr-1"></div>
            </div>

            <div class="xl:col-span-4 rounded-3xl border border-amber-200 bg-gradient-to-br from-white to-amber-50/70 p-5 shadow-sm flex flex-col min-h-0">
                <div class="mb-4 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">License Alerts</h3>
                        <p class="mt-1 text-sm text-slate-500">Expiring within the next 60 days</p>
                    </div>
                    <div id="licenseAlertsCount" class="rounded-2xl bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700">0</div>
                </div>

                <div id="licenseAlertsList" class="flex-1 min-h-0 overflow-y-auto space-y-3 pr-1"></div>
            </div>
        </div>
    </div>

    <script>
        const guards = @json(json_decode($guardsJson, true));

        function getManilaNow() {
            const now = new Date();
            const manilaString = now.toLocaleString('en-US', { timeZone: 'Asia/Manila' });
            return new Date(manilaString);
        }

        function parseDateLocal(dateString) {
            if (!dateString) return null;
            const [year, month, day] = dateString.split('-').map(Number);
            return new Date(year, month - 1, day);
        }

        function startOfDay(date) {
            return new Date(date.getFullYear(), date.getMonth(), date.getDate());
        }

        function diffInDays(fromDate, toDate) {
            const msPerDay = 1000 * 60 * 60 * 24;
            return Math.round((startOfDay(toDate) - startOfDay(fromDate)) / msPerDay);
        }

        function formatDate(date) {
            return date.toLocaleDateString('en-US', {
                timeZone: 'Asia/Manila',
                month: 'long',
                day: '2-digit',
                year: 'numeric',
            });
        }

        function formatShortDate(date) {
            return date.toLocaleDateString('en-US', {
                timeZone: 'Asia/Manila',
                month: 'short',
                day: '2-digit',
            });
        }

        function getNextBirthday(birthdate, today) {
            const next = new Date(today.getFullYear(), birthdate.getMonth(), birthdate.getDate());
            if (startOfDay(next) < startOfDay(today)) {
                next.setFullYear(today.getFullYear() + 1);
            }
            return next;
        }

        function renderBirthdayToday(list) {
            const container = document.getElementById('birthdayTodayList');
            document.getElementById('birthdayTodayCount').textContent = list.length;

            if (!list.length) {
                container.innerHTML = `
                    <div class="flex h-full min-h-[180px] items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white/70 text-center">
                        <div>
                            <p class="font-semibold text-slate-700">No birthdays today</p>
                            <p class="mt-1 text-sm text-slate-500">No guard is celebrating today.</p>
                        </div>
                    </div>
                `;
                return;
            }

            container.innerHTML = list.map(guard => `
                <div class="rounded-2xl border border-pink-200 bg-white/80 p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">${guard.full_name}</p>
                            <p class="mt-1 text-xs text-slate-500">${guard.company_name ?? '—'}</p>
                        </div>
                        <span class="rounded-full bg-pink-100 px-3 py-1 text-[11px] font-semibold text-pink-700">Today</span>
                    </div>
                    <div class="mt-3 rounded-xl bg-pink-50 px-3 py-2">
                        <p class="text-xs text-slate-500">Birthdate</p>
                        <p class="text-sm font-medium text-slate-800">${formatDate(guard.birthdateObj)}</p>
                    </div>
                </div>
            `).join('');
        }

        function renderUpcomingBirthdays(list) {
            const container = document.getElementById('upcomingBirthdaysList');
            document.getElementById('upcomingBirthdaysCount').textContent = list.length;

            if (!list.length) {
                container.innerHTML = `
                    <div class="flex h-full min-h-[180px] items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white/70 text-center">
                        <p class="text-sm text-slate-500">No upcoming birthdays found.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = list.map(guard => `
                <div class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-900">${guard.full_name}</p>
                        <p class="mt-1 truncate text-xs text-slate-500">${guard.company_name ?? '—'}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-semibold text-slate-800">${formatShortDate(guard.nextBirthday)}</p>
                        <p class="text-xs text-slate-500">${guard.daysUntilBirthday} day${guard.daysUntilBirthday !== 1 ? 's' : ''}</p>
                    </div>
                </div>
            `).join('');
        }

        function renderLicenseAlerts(list) {
            const container = document.getElementById('licenseAlertsList');
            document.getElementById('licenseAlertsCount').textContent = list.length;

            if (!list.length) {
                container.innerHTML = `
                    <div class="flex h-full min-h-[180px] items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white/70 text-center">
                        <p class="text-sm text-slate-500">No license alerts within 60 days.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = list.map(guard => `
                <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900">${guard.full_name}</p>
                            <p class="mt-1 truncate text-xs text-slate-500">${guard.company_name ?? '—'}</p>
                        </div>
                        <span class="rounded-full ${guard.daysLeft <= 30 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'} px-3 py-1 text-[11px] font-semibold">
                            ${guard.daysLeft <= 30 ? '30 Days' : '60 Days'}
                        </span>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-slate-50 px-3 py-2">
                            <p class="text-xs text-slate-500">Validity</p>
                            <p class="text-sm font-medium text-slate-800">${formatDate(guard.licenseDateObj)}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 px-3 py-2">
                            <p class="text-xs text-slate-500">Days Left</p>
                            <p class="text-sm font-semibold ${guard.daysLeft <= 30 ? 'text-red-600' : 'text-amber-600'}">
                                ${guard.daysLeft} day${guard.daysLeft !== 1 ? 's' : ''}
                            </p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function updateDashboardLiveData() {
            const today = startOfDay(getManilaNow());

            const enriched = guards.map(guard => {
                const birthdateObj = parseDateLocal(guard.birthdate);
                const licenseDateObj = parseDateLocal(guard.license_validity_date);

                let nextBirthday = null;
                let daysUntilBirthday = null;
                if (birthdateObj) {
                    nextBirthday = getNextBirthday(birthdateObj, today);
                    daysUntilBirthday = diffInDays(today, nextBirthday);
                }

                let daysLeft = null;
                if (licenseDateObj) {
                    daysLeft = diffInDays(today, licenseDateObj);
                }

                return {
                    ...guard,
                    birthdateObj,
                    licenseDateObj,
                    nextBirthday,
                    daysUntilBirthday,
                    daysLeft,
                };
            });

            const birthdayToday = enriched.filter(g =>
                g.birthdateObj &&
                g.birthdateObj.getMonth() === today.getMonth() &&
                g.birthdateObj.getDate() === today.getDate()
            );

            const upcomingBirthdays = enriched
                .filter(g => g.daysUntilBirthday !== null && g.daysUntilBirthday > 0)
                .sort((a, b) => a.daysUntilBirthday - b.daysUntilBirthday)
                .slice(0, 6);

            const expiredLicenses = enriched.filter(g => g.daysLeft !== null && g.daysLeft < 0);
            const expiring30Days = enriched.filter(g => g.daysLeft !== null && g.daysLeft >= 0 && g.daysLeft <= 30);
            const expiring60Days = enriched.filter(g => g.daysLeft !== null && g.daysLeft >= 31 && g.daysLeft <= 60);
            const activeGuards = enriched.filter(g => g.daysLeft !== null && g.daysLeft > 60);

            const licenseAlerts = enriched
                .filter(g => g.daysLeft !== null && g.daysLeft >= 0 && g.daysLeft <= 60)
                .sort((a, b) => a.daysLeft - b.daysLeft)
                .slice(0, 8);

            document.getElementById('cardActiveGuards').textContent = activeGuards.length;
            document.getElementById('cardExpiredLicenses').textContent = expiredLicenses.length;
            document.getElementById('cardExpiring30Days').textContent = expiring30Days.length;
            document.getElementById('cardExpiring60Days').textContent = expiring60Days.length;

            renderBirthdayToday(birthdayToday);
            renderUpcomingBirthdays(upcomingBirthdays);
            renderLicenseAlerts(licenseAlerts);
        }

        updateDashboardLiveData();
        setInterval(updateDashboardLiveData, 1000);
    </script>
@endsection