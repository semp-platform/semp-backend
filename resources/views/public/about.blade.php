@extends('layouts.public')

@section('title', 'About OGSIEC')

@section(
    'meta_description',
    'Learn about the Ogun State Independent Electoral Commission, its mandate, leadership and role in local government elections in Ogun State.'
)

@section('content')

{{-- =========================================================
     ABOUT HERO
     ========================================================= --}}

<section class="relative overflow-hidden bg-slate-950">

    <div
        class="absolute inset-0 bg-cover bg-center opacity-25"
        style="background-image: url('{{ asset('images/olumo_rock.jpg') }}');"
    ></div>

    <div class="absolute inset-0 bg-slate-950/75"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">

        <div class="max-w-3xl">

            <p class="text-xs font-bold uppercase tracking-widest text-emerald-400">
                About OGSIEC
            </p>

            <h1 class="mt-3 text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">
                Ogun State Independent Electoral Commission
            </h1>

            <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                The Ogun State Independent Electoral Commission (OGSIEC)
                is responsible for organising, undertaking and supervising
                local government elections in Ogun State.
            </p>

        </div>

    </div>

</section>


{{-- =========================================================
     DRAFT NOTICE
     ========================================================= --}}

<section class="border-b border-amber-200 bg-amber-50">

    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">

        <p class="text-sm leading-6 text-amber-900">
            <strong>Draft information:</strong>
            This page contains preliminary public information compiled
            from available sources. Official biographies and institutional
            information should be reviewed and approved by OGSIEC before
            final publication.
        </p>

    </div>

</section>


{{-- =========================================================
     ABOUT THE COMMISSION
     ========================================================= --}}

<section class="py-14 sm:py-16">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="grid gap-10 lg:grid-cols-[1.3fr_0.7fr]">

            <div>

                <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                    The Commission
                </p>

                <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                    About OGSIEC
                </h2>

                <div class="mt-6 space-y-5 text-sm leading-7 text-slate-600 sm:text-base">

                    <p>
                        The Ogun State Independent Electoral Commission
                        (OGSIEC) is the electoral management body responsible
                        for local government elections in Ogun State.
                    </p>

                    <p>
                        The Commission's work includes the organisation,
                        undertaking and supervision of local government
                        elections, electoral preparation, engagement with
                        political parties and other stakeholders, and the
                        administration of procedures relating to candidates
                        and elections.
                    </p>

                    <p>
                        Through its electoral responsibilities, OGSIEC
                        supports democratic participation at the local
                        government level and provides an institutional
                        framework for eligible citizens, political parties
                        and candidates to participate in local elections.
                    </p>

                </div>

            </div>


            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">

                <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                    Our Role
                </p>

                <ul class="mt-5 space-y-4 text-sm leading-6 text-slate-700">

                    <li class="flex gap-3">
                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-emerald-600"></span>
                        <span>Organising local government elections.</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-emerald-600"></span>
                        <span>Undertaking and supervising electoral activities.</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-emerald-600"></span>
                        <span>Working with political parties and electoral stakeholders.</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="mt-2 h-2 w-2 shrink-0 rounded-full bg-emerald-600"></span>
                        <span>Supporting orderly and transparent electoral processes.</span>
                    </li>

                </ul>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     LEADERSHIP
     ========================================================= --}}

<section class="border-y border-slate-200 bg-slate-50 py-14 sm:py-16">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="max-w-2xl">

            <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                Leadership
            </p>

            <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                Leadership of the Commission
            </h2>

            <p class="mt-4 text-sm leading-6 text-slate-600 sm:text-base">
                The current leadership of OGSIEC comprises the Chairman
                and four Commissioners.
            </p>

        </div>


        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Chairman --}}

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex h-56 items-center justify-center bg-slate-900">

                    <div class="flex h-28 w-28 items-center justify-center rounded-full bg-emerald-700 text-3xl font-bold text-white">
                        BO
                    </div>

                </div>

                <div class="p-6">

                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                        Chairman
                    </p>

                    <h3 class="mt-2 text-xl font-bold text-slate-950">
                        Babatunde Adetona Osibodu
                    </h3>

                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Mr. Babatunde Adetona Osibodu serves as Chairman
                        of the Ogun State Independent Electoral Commission.
                        He leads the Commission in the administration and
                        supervision of local government electoral activities
                        in Ogun State.
                    </p>

                </div>

            </article>


            {{-- Akoni --}}

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex h-56 items-center justify-center bg-slate-900">

                    <div class="flex h-28 w-28 items-center justify-center rounded-full bg-emerald-700 text-3xl font-bold text-white">
                        OA
                    </div>

                </div>

                <div class="p-6">

                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                        Commissioner
                    </p>

                    <h3 class="mt-2 text-xl font-bold text-slate-950">
                        Olatunji Akoni
                    </h3>

                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Mr. Olatunji Akoni serves as a Commissioner of OGSIEC
                        and contributes to the Commission's electoral
                        administration and stakeholder responsibilities.
                    </p>

                </div>

            </article>


            {{-- Bankole --}}

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex h-56 items-center justify-center bg-slate-900">

                    <div class="flex h-28 w-28 items-center justify-center rounded-full bg-emerald-700 text-3xl font-bold text-white">
                        DB
                    </div>

                </div>

                <div class="p-6">

                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                        Commissioner
                    </p>

                    <h3 class="mt-2 text-xl font-bold text-slate-950">
                        Dele Bankole
                    </h3>

                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Mr. Dele Bankole serves as a Commissioner of OGSIEC,
                        contributing to the Commission's mandate and
                        administration of local government electoral
                        activities.
                    </p>

                </div>

            </article>


            {{-- Onasanya --}}

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex h-56 items-center justify-center bg-slate-900">

                    <div class="flex h-28 w-28 items-center justify-center rounded-full bg-emerald-700 text-3xl font-bold text-white">
                        GO
                    </div>

                </div>

                <div class="p-6">

                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                        Commissioner
                    </p>

                    <h3 class="mt-2 text-xl font-bold text-slate-950">
                        Gbemi Onasanya
                    </h3>

                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Mrs. Gbemi Onasanya serves as a Commissioner of OGSIEC
                        and participates in the Commission's electoral and
                        stakeholder responsibilities.
                    </p>

                </div>

            </article>


            {{-- Olaopa --}}

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex h-56 items-center justify-center bg-slate-900">

                    <div class="flex h-28 w-28 items-center justify-center rounded-full bg-emerald-700 text-3xl font-bold text-white">
                        RO
                    </div>

                </div>

                <div class="p-6">

                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">
                        Commissioner for Legal Affairs
                    </p>

                    <h3 class="mt-2 text-xl font-bold text-slate-950">
                        Remilekun O. Olaopa
                    </h3>

                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Mrs. Remilekun O. Olaopa is a legal practitioner
                        serving as Commissioner for Legal Affairs at OGSIEC.
                        Her professional background includes legal practice,
                        litigation, arbitration and alternative dispute
                        resolution.
                    </p>

                </div>

            </article>

        </div>

    </div>

</section>


{{-- =========================================================
     PUBLIC SERVICE
     ========================================================= --}}

<section class="py-14 sm:py-16">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="rounded-2xl bg-emerald-950 px-6 py-10 text-white sm:px-10">

            <p class="text-xs font-bold uppercase tracking-widest text-emerald-300">
                Public Information
            </p>

            <h2 class="mt-2 text-2xl font-bold sm:text-3xl">
                Electoral Information for Ogun State
            </h2>

            <p class="mt-4 max-w-3xl text-sm leading-6 text-emerald-100 sm:text-base">
                Access public information about elections, candidates,
                notices, publications and other electoral activities
                through the OGSIEC public information portal.
            </p>

            <div class="mt-6 flex flex-wrap gap-3">

                <a
                    href="{{ route('public.elections.index') }}"
                    class="inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-sm font-bold text-emerald-900 transition hover:bg-emerald-50"
                >
                    View Elections
                </a>

                <a
                    href="{{ route('public.candidates.index') }}"
                    class="inline-flex items-center rounded-lg border border-white/20 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-white/10"
                >
                    View Candidates
                </a>

            </div>

        </div>

    </div>

</section>

@endsection
