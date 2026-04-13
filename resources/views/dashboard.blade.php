@extends('layouts.app')

@section('content')
    <div class="h-[calc(100vh-13.5rem)] flex flex-col gap-5 overflow-hidden">
        <div class="flex items-center justify-between shrink-0">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Operations Overview</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Monitor personnel status, birthday reminders, and live license alerts.
                </p>
            </div>

            <div class="rounded-2xl bg-gradient-to-r from-slate-900 to-slate-700 px-4 py-2 text-sm font-medium text-white shadow-lg">
                Live Status Board
            </div>
        </div>

        <div class="grid grid-cols-2 xl:grid-cols-5 gap-4 shrink-0">
            <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Total Guards</p>
                <h2 id="cardTotalGuards" class="mt-3 text-3xl font-bold text-slate-900">{{ $totalGuards }}</h2>
                <p class="mt-2 text-xs text-slate-400">Total personnel in the system</p>
            </div>

            <div class="rounded-3xl border border-emerald-200 bg-gradient-to-br from-white to-emerald-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Active</p>
                <h2 id="cardActiveGuards" class="mt-3 text-3xl font-bold text-emerald-600">0</h2>
                <p class="mt-2 text-xs text-slate-400">Valid and operational</p>
            </div>

            <div class="rounded-3xl border border-red-200 bg-gradient-to-br from-white to-red-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Expired</p>
                <h2 id="cardExpiredLicenses" class="mt-3 text-3xl font-bold text-red-600">0</h2>
                <p class="mt-2 text-xs text-slate-400">Needs immediate attention</p>
            </div>

            <div class="rounded-3xl border border-amber-200 bg-gradient-to-br from-white to-amber-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">30 Days</p>
                <h2 id="cardExpiring30Days" class="mt-3 text-3xl font-bold text-amber-600">0</h2>
                <p class="mt-2 text-xs text-slate-400">Urgent renewals soon</p>
            </div>

            <div class="rounded-3xl border border-yellow-200 bg-gradient-to-br from-white to-yellow-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">60 Days</p>
                <h2 id="cardExpiring60Days" class="mt-3 text-3xl font-bold text-yellow-600">0</h2>
                <p class="mt-2 text-xs text-slate-400">Upcoming renewals</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 flex-1 min-h-0">
            <div class="xl:col-span-4 rounded-3xl border border-slate-200 bg-gradient-to-br from-white to-pink-50/40 p-5 shadow-sm flex flex-col min-h-0">
                <div class="mb-4 shrink-0 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Birthday Today</h2>
                        <p class="mt-1 text-sm text-slate-500">Celebrants for today</p>
                    </div>
                    <div id="birthdayTodayCount" class="rounded-2xl bg-pink-100 px-3 py-2 text-xs font-semibold text-pink-700">
                        0
                    </div>
                </div>

                <div id="birthdayTodayList" class="flex-1 min-h-0 overflow-y-auto space-y-3 pr-1"></div>
            </div>

            <div class="xl:col-span-4 rounded-3xl border border-slate-200 bg-gradient-to-br from-white to-blue-50/40 p-5 shadow-sm flex flex-col min-h-0">
                <div class="mb-4 shrink-0 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Upcoming Birthdays</h2>
                        <p class="mt-1 text-sm text-slate-500">Next scheduled celebrations</p>
                    </div>
                    <div id="upcomingBirthdaysCount" class="rounded-2xl bg-blue-100 px-3 py-2 text-xs font-semibold text-blue-700">
                        0
                    </div>
                </div>

                <div id="upcomingBirthdaysList" class="flex-1 min-h-0 overflow-y-auto space-y-3 pr-1"></div>
            </div>

            <div class="xl:col-span-4 rounded-3xl border border-slate-200 bg-gradient-to-br from-white to-amber-50/40 p-5 shadow-sm flex flex-col min-h-0">
                <div class="mb-4 shrink-0 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">License Alerts</h2>
                        <p class="mt-1 text-sm text-slate-500">Expiring within 60 days</p>
                    </div>
                    <div id="licenseAlertsCount" class="rounded-2xl bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-700">
                        0
                    </div>
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
            const from = startOfDay(fromDate);
            const to = startOfDay(toDate);
            return Math.round((to - from) / msPerDay);
        }

        function formatDate(date) {
            return date.toLocaleDateString('en-US', {
                timeZone: 'Asia/Manila',
                month: 'short',
                day: '2-digit',
                year: 'numeric',
            });
        }

        function formatMonthDay(date) {
            return date.toLocaleDateString('en-US', {
                timeZone: 'Asia/Manila',
                month: 'short',
                day: '2-digit',
            });
        }

        function getNextBirthday(birthdate, today) {
            const nextBirthday = new Date(
                today.getFullYear(),
                birthdate.getMonth(),
                birthdate.getDate()
            );

            if (startOfDay(nextBirthday) < startOfDay(today)) {
                nextBirthday.setFullYear(today.getFullYear() + 1);
            }

            return nextBirthday;
        }

        function badgeClass(type) {
            if (type === '30') {
                return 'rounded-full bg-red-100 px-3 py-1 text-[11px] font-semibold text-red-700';
            }
            if (type === '60') {
                return 'rounded-full bg-amber-100 px-3 py-1 text-[11px] font-semibold text-amber-700';
            }
            return 'rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-700';
        }

        function renderBirthdayToday(list) {
            const container = document.getElementById('birthdayTodayList');
            const count = document.getElementById('birthdayTodayCount');
            count.textContent = list.length;

            if (!list.length) {
                container.innerHTML = `
                    <div class="flex h-full min-h-[180px] items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-center">
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
            const count = document.getElementById('upcomingBirthdaysCount');
            count.textContent = list.length;

            if (!list.length) {
                container.innerHTML = `
                    <div class="flex h-full min-h-[180px] items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-center">
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
                        <p class="text-sm font-semibold text-slate-800">${formatMonthDay(guard.nextBirthday)}</p>
                        <p class="text-xs text-slate-500">${guard.daysUntilBirthday} day${guard.daysUntilBirthday !== 1 ? 's' : ''}</p>
                    </div>
                </div>
            `).join('');
        }

        function renderLicenseAlerts(list) {
            const container = document.getElementById('licenseAlertsList');
            const count = document.getElementById('licenseAlertsCount');
            count.textContent = list.length;

            if (!list.length) {
                container.innerHTML = `
                    <div class="flex h-full min-h-[180px] items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-center">
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

                        <span class="${badgeClass(guard.daysLeft <= 30 ? '30' : '60')}">
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
            const now = getManilaNow();
            const today = startOfDay(now);

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

            const birthdayToday = enriched.filter(guard => {
                if (!guard.birthdateObj) return false;
                return guard.birthdateObj.getMonth() === today.getMonth()
                    && guard.birthdateObj.getDate() === today.getDate();
            });

            const upcomingBirthdays = enriched
                .filter(guard => guard.daysUntilBirthday !== null && guard.daysUntilBirthday > 0)
                .sort((a, b) => a.daysUntilBirthday - b.daysUntilBirthday)
                .slice(0, 6);

            const expiredLicenses = enriched.filter(guard => guard.daysLeft !== null && guard.daysLeft < 0);
            const expiring30Days = enriched.filter(guard => guard.daysLeft !== null && guard.daysLeft >= 0 && guard.daysLeft <= 30);
            const expiring60Days = enriched.filter(guard => guard.daysLeft !== null && guard.daysLeft >= 31 && guard.daysLeft <= 60);
            const activeGuards = enriched.filter(guard => guard.daysLeft !== null && guard.daysLeft > 60);

            const licenseAlerts = enriched
                .filter(guard => guard.daysLeft !== null && guard.daysLeft >= 0 && guard.daysLeft <= 60)
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